<?php

namespace cover\base;

/**
 * UnknownMethodException represents an exception caused by accessing an unknown object method.
 *
 * @since 2.0
 */
class UnknownMethodException extends \BadMethodCallException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Unknown Method';
    }
}
