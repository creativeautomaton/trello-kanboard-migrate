<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInited3f8cff9041421542b4db2f320b77c9
{
    public static $prefixesPsr0 = array (
        'J' => 
        array (
            'JsonRPC' => 
            array (
                0 => __DIR__ . '/..' . '/fguillot/json-rpc/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInited3f8cff9041421542b4db2f320b77c9::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
