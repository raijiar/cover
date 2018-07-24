<?php

namespace cover\base;

/**
 * StaticInstanceInterface is the interface for providing static instances to classes,
 * which can be used to obtain class meta information that can not be expressed in static methods.
 * For example: adjustments made by DI or behaviors reveal only at object level, but might be needed
 * at class (static) level as well.
 *
 * To implement the [[instance()]] method you may use [[StaticInstanceTrait]].
 *
 * @since 1.0
 * @see StaticInstanceTrait
 */
interface StaticInstanceInterface
{
    /**
     * Returns static class instance, which can be used to obtain meta information.
     * @param bool $refresh whether to re-create static instance even, if it is already cached.
     * @return static class instance.
     */
    public static function instance($refresh = false);
}
