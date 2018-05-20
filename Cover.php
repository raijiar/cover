<?php

require __DIR__ . '/BaseCover.php';

/**
 * Cover is a helper class serving common framework functionalities.
 *
 * It extends from [[\cover\BaseCover]] which provides the actual implementation.
 * By writing your own Cover class, you can customize some functionalities of [[\cover\BaseCover]].
 *
 */
class Cover extends \cover\BaseCover
{
}

spl_autoload_register(['Cover', 'autoload'], true, true);
Cover::$classMap = require __DIR__ . '/classes.php';
Cover::$container = new cover\di\Container();
