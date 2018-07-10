<?php

namespace cover\validators;

use cover\web\AssetBundle;

/**
 * This asset bundle provides the javascript files needed for the [[EmailValidator]]s client validation.
 *
 * @since 1.0
 */
class PunycodeAsset extends AssetBundle
{
    public $sourcePath = '@bower/punycode';
    public $js = [
        'punycode.js',
    ];
}
