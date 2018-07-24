<?php

namespace cover\base;

/**
 * UnknownClassException represents an exception caused by using an unknown class.
 *
 * @since 1.0
 */
class UnknownClassException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Unknown Class';
    }
}
