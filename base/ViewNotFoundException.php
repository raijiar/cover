<?php

namespace cover\base;

/**
 * ViewNotFoundException represents an exception caused by view file not found.
 *
 * @since 1.0
 */
class ViewNotFoundException extends InvalidArgumentException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'View not Found';
    }
}
