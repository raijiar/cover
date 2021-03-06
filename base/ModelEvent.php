<?php

namespace cover\base;

/**
 * ModelEvent represents the parameter needed by [[Model]] events.
 *
 * @since 1.0
 */
class ModelEvent extends Event
{
    /**
     * @var bool whether the model is in valid status. Defaults to true.
     * A model is in valid status if it passes validations or certain checks.
     */
    public $isValid = true;
}
