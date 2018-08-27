<?php

namespace cover\db;

use cover\base\BaseObject;

/**
 * Constraint represents the metadata of a table constraint.
 *
 * @since 1.0
 */
class Constraint extends BaseObject
{
    /**
     * @var string[]|null list of column names the constraint belongs to.
     */
    public $columnNames;
    /**
     * @var string|null the constraint name.
     */
    public $name;
}
