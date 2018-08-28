<?php

namespace cover\mail;

use cover\base\Event;

/**
 * MailEvent represents the event parameter used for events triggered by [[BaseMailer]].
 *
 * By setting the [[isValid]] property, one may control whether to continue running the action.
 *
 * @since 1.0
 */
class MailEvent extends Event
{
    /**
     * @var \cover\mail\MessageInterface the mail message being send.
     */
    public $message;
    /**
     * @var bool if message was sent successfully.
     */
    public $isSuccessful;
    /**
     * @var bool whether to continue sending an email. Event handlers of
     * [[\cover\mail\BaseMailer::EVENT_BEFORE_SEND]] may set this property to decide whether
     * to continue send or not.
     */
    public $isValid = true;
}
