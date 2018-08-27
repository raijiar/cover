<?php

namespace cover\db;

/**
 * Expression represents a DB expression that does not need escaping or quoting.
 *
 * When an Expression object is embedded within a SQL statement or fragment,
 * it will be replaced with the [[expression]] property value without any
 * DB escaping or quoting. For example,
 *
 * ```php
 * $expression = new Expression('NOW()');
 * $now = (new \cover\db\Query)->select($expression)->scalar();  // SELECT NOW();
 * echo $now; // prints the current date
 * ```
 *
 * Expression objects are mainly created for passing raw SQL expressions to methods of
 * [[Query]], [[ActiveQuery]], and related classes.
 *
 * An expression can also be bound with parameters specified via [[params]].
 *
 * @since 1.0
 */
class Expression extends \cover\base\BaseObject implements ExpressionInterface
{
    /**
     * @var string the DB expression
     */
    public $expression;
    /**
     * @var array list of parameters that should be bound for this expression.
     * The keys are placeholders appearing in [[expression]] and the values
     * are the corresponding parameter values.
     */
    public $params = [];


    /**
     * Constructor.
     * @param string $expression the DB expression
     * @param array $params parameters
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($expression, $params = [], $config = [])
    {
        $this->expression = $expression;
        $this->params = $params;
        parent::__construct($config);
    }

    /**
     * String magic method.
     * @return string the DB expression.
     */
    public function __toString()
    {
        return $this->expression;
    }
}
