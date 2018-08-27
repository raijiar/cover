<?php

namespace cover\db;

use cover\base\Event;

/**
 * AfterSaveEvent represents the information available in [[ActiveRecord::EVENT_AFTER_INSERT]] and [[ActiveRecord::EVENT_AFTER_UPDATE]].
 *
 * @since 1.0
 */
class AfterSaveEvent extends Event
{
    /**
     * @var array The attribute values that had changed and were saved.
     */
    public $changedAttributes;
}
