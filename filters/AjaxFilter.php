<?php

namespace cover\filters;

use Cover;
use cover\base\ActionFilter;
use cover\web\BadRequestHttpException;
use cover\web\Request;

/**
 * AjaxFilter allow to limit access only for ajax requests.
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => 'cover\filters\AjaxFilter',
 *             'only' => ['index']
 *         ],
 *     ];
 * }
 * ```
 *
 * @since 1.0
 */
class AjaxFilter extends ActionFilter
{
    /**
     * @var string the message to be displayed when request isn't ajax
     */
    public $errorMessage = 'Request must be XMLHttpRequest.';
    /**
     * @var Request the current request. If not set, the `request` application component will be used.
     */
    public $request;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->request === null) {
            $this->request = Cover::$app->getRequest();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if ($this->request->getIsAjax()) {
            return true;
        }

        throw new BadRequestHttpException($this->errorMessage);
    }
}
