<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit79644ad6ab093db60b63d78ef79a8826
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit79644ad6ab093db60b63d78ef79a8826::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit79644ad6ab093db60b63d78ef79a8826::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit79644ad6ab093db60b63d78ef79a8826::$classMap;

        }, null, ClassLoader::class);
    }
}
