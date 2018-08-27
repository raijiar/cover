<?php

namespace cover\db;

/**
 * DefaultValueConstraint represents the metadata of a table `DEFAULT` constraint.
 *
 * @since 1.0
 */
class DefaultValueConstraint extends Constraint
{
    /**
     * @var mixed default value as returned by the DBMS.
     */
    public $value;
}
