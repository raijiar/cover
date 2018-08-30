<?php

namespace cover\log;

use Cover;
use cover\base\InvalidConfigException;
use cover\di\Instance;
use cover\mail\MailerInterface;

/**
 * EmailTarget sends selected log messages to the specified email addresses.
 *
 * You may configure the email to be sent by setting the [[message]] property, through which
 * you can set the target email addresses, subject, etc.:
 *
 * ```php
 * 'components' => [
 *     'log' => [
 *          'targets' => [
 *              [
 *                  'class' => 'cover\log\EmailTarget',
 *                  'mailer' => 'mailer',
 *                  'levels' => ['error', 'warning'],
 *                  'message' => [
 *                      'from' => ['log@example.com'],
 *                      'to' => ['developer1@example.com', 'developer2@example.com'],
 *                      'subject' => 'Log message',
 *                  ],
 *              ],
 *          ],
 *     ],
 * ],
 * ```
 *
 * In the above `mailer` is ID of the component that sends email and should be already configured.
 *
 * @since 1.0
 */
class EmailTarget extends Target
{
    /**
     * @var array the configuration array for creating a [[\cover\mail\MessageInterface|message]] object.
     * Note that the "to" option must be set, which specifies the destination email address(es).
     */
    public $message = [];
    /**
     * @var MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     * After the EmailTarget object is created, if you want to change this property, you should only assign it
     * with a mailer object.
     * Starting from version 1.0, this can also be a configuration array for creating the object.
     */
    public $mailer = 'mailer';


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (empty($this->message['to'])) {
            throw new InvalidConfigException('The "to" option must be set for EmailTarget::message.');
        }
        $this->mailer = Instance::ensure($this->mailer, 'cover\mail\MailerInterface');
    }

    /**
     * Sends log messages to specified email addresses.
     * Starting from version 1.0, this method throws LogRuntimeException in case the log can not be exported.
     * @throws LogRuntimeException
     */
    public function export()
    {
        // moved initialization of subject here because of the following issue
        // https://github.com/coversoft/cover2/issues/1446
        if (empty($this->message['subject'])) {
            $this->message['subject'] = 'Application Log';
        }
        $messages = array_map([$this, 'formatMessage'], $this->messages);
        $body = wordwrap(implode("\n", $messages), 70);
        $message = $this->composeMessage($body);
        if (!$message->send($this->mailer)) {
            throw new LogRuntimeException('Unable to export log through email!');
        }
    }

    /**
     * Composes a mail message with the given body content.
     * @param string $body the body content
     * @return \cover\mail\MessageInterface $message
     */
    protected function composeMessage($body)
    {
        $message = $this->mailer->compose();
        Cover::configure($message, $this->message);
        $message->setTextBody($body);

        return $message;
    }
}
