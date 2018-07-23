<?php

namespace cover\base;

/**
 * NotSupportedException represents an exception caused by accessing features that are not supported.
 *
 * @since 1.0
 */
class NotSupportedException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Not Supported';
    }
}
