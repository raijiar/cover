<?php

namespace cover\db;

/**
 * ForeignKeyConstraint represents the metadata of a table `FOREIGN KEY` constraint.
 *
 * @since 1.0
 */
class ForeignKeyConstraint extends Constraint
{
    /**
     * @var string|null referenced table schema name.
     */
    public $foreignSchemaName;
    /**
     * @var string referenced table name.
     */
    public $foreignTableName;
    /**
     * @var string[] list of referenced table column names.
     */
    public $foreignColumnNames;
    /**
     * @var string|null referential action if rows in a referenced table are to be updated.
     */
    public $onUpdate;
    /**
     * @var string|null referential action if rows in a referenced table are to be deleted.
     */
    public $onDelete;
}
