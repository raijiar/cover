<?php

namespace cover\console;

use cover\base\UserException;

/**
 * Exception represents an exception caused by incorrect usage of a console command.
 *
 * @since 1.0
 */
class Exception extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Error';
    }
}
