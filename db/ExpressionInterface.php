<?php

namespace cover\db;

/**
 * Interface ExpressionInterface should be used to mark classes, that should be built
 * in a special way.
 *
 * The database abstraction layer of Yii framework supports objects that implement this
 * interface and will use [[ExpressionBuilderInterface]] to build them.
 *
 * The default implementation is a class [[Expression]].
 *
 * @since 1.0
 */
interface ExpressionInterface
{
}
