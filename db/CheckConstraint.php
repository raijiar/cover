<?php

namespace cover\db;

/**
 * CheckConstraint represents the metadata of a table `CHECK` constraint.
 *
 * @since 1.0
 */
class CheckConstraint extends Constraint
{
    /**
     * @var string the SQL of the `CHECK` constraint.
     */
    public $expression;
}
