<?php

namespace cover\db;

/**
 * Exception represents an exception that is caused by violation of DB constraints.
 *
 * @since 1.0
 */
class IntegrityException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Integrity constraint violation';
    }
}
