<?php

namespace cover\log;

/**
 * LogRuntimeException represents an exception caused by problems with log delivery.
 *
 * @since 1.0
 */
class LogRuntimeException extends \cover\base\Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Log Runtime';
    }
}
