<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb20f5afaac3eb8979dda23575777744a
{
    public static $files = array (
        '2f5abe988f63e3bfd5d1f2f9cc28a764' => __DIR__ . '/../..' . '/helpers/helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SpondonIt\\Service\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SpondonIt\\Service\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitb20f5afaac3eb8979dda23575777744a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb20f5afaac3eb8979dda23575777744a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb20f5afaac3eb8979dda23575777744a::$classMap;

        }, null, ClassLoader::class);
    }
}
