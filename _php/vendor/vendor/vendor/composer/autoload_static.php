<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaead1b2fa2fbf8c2c9af6f127739f0cf
{
    public static $classMap = array (
        'upload' => __DIR__ . '/..' . '/verot/class.upload.php/src/class.upload.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitaead1b2fa2fbf8c2c9af6f127739f0cf::$classMap;

        }, null, ClassLoader::class);
    }
}