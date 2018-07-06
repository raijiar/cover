<?php

namespace cover\rest;

use Cover;
use cover\data\ActiveDataProvider;
use cover\data\DataFilter;

/**
 * IndexAction implements the API endpoint for listing multiple models.
 *
 * For more details and usage information on IndexAction, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class IndexAction extends Action
{
    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function (IndexAction $action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return an instance of [[ActiveDataProvider]].
     *
     * If [[dataFilter]] is set the result of [[DataFilter::build()]] will be passed to the callable as a second parameter.
     * In this case the signature of the callable should be the following:
     *
     * ```php
     * function (IndexAction $action, mixed $filter) {
     *     // $action is the action object currently running
     *     // $filter the built filter condition
     * }
     * ```
     */
    public $prepareDataProvider;
    /**
     * @var DataFilter|null data filter to be used for the search filter composition.
     * You must setup this field explicitly in order to enable filter processing.
     * For example:
     *
     * ```php
     * [
     *     'class' => 'cover\data\ActiveDataFilter',
     *     'searchModel' => function () {
     *         return (new \cover\base\DynamicModel(['id' => null, 'name' => null, 'price' => null]))
     *             ->addRule('id', 'integer')
     *             ->addRule('name', 'trim')
     *             ->addRule('name', 'string')
     *             ->addRule('price', 'number');
     *     },
     * ]
     * ```
     *
     * @see DataFilter
     *
     * @since 2.0.13
     */
    public $dataFilter;


    /**
     * @return ActiveDataProvider
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        $requestParams = Cover::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Cover::$app->getRequest()->getQueryParams();
        }

        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Cover::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {
                $filter = $this->dataFilter->build();
                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this, $filter);
        }

        /* @var $modelClass \cover\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::find();
        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        return Cover::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }
}
