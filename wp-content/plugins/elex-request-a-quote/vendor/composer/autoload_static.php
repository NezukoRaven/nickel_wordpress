<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7707c89f19aaec58e5066a5885670f47
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Elex\\RequestAQuote\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Elex\\RequestAQuote\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7707c89f19aaec58e5066a5885670f47::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7707c89f19aaec58e5066a5885670f47::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7707c89f19aaec58e5066a5885670f47::$classMap;

        }, null, ClassLoader::class);
    }
}