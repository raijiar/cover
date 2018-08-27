<?php

namespace cover\db;

/**
 * IndexConstraint represents the metadata of a table `INDEX` constraint.
 *
 * @since 1.0
 */
class IndexConstraint extends Constraint
{
    /**
     * @var bool whether the index is unique.
     */
    public $isUnique;
    /**
     * @var bool whether the index was created for a primary key.
     */
    public $isPrimary;
}
