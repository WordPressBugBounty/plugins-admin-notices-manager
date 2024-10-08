<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf52094fe2615ae7aa1833411942eb01e
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AdminNoticesManager\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AdminNoticesManager\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/classes',
        ),
    );

    public static $classMap = array (
        'AdminNoticesManager\\Notices' => __DIR__ . '/../..' . '/includes/classes/class-notices.php',
        'AdminNoticesManager\\Pointers' => __DIR__ . '/../..' . '/includes/classes/class-pointers.php',
        'AdminNoticesManager\\Rational_Option_Pages' => __DIR__ . '/../..' . '/includes/classes/vendor/class-rational-option-pages.php',
        'AdminNoticesManager\\Select2_WPWS' => __DIR__ . '/../..' . '/includes/classes/vendor/Select2/class-select2-wpws.php',
        'AdminNoticesManager\\Settings' => __DIR__ . '/../..' . '/includes/classes/class-settings.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf52094fe2615ae7aa1833411942eb01e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf52094fe2615ae7aa1833411942eb01e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf52094fe2615ae7aa1833411942eb01e::$classMap;

        }, null, ClassLoader::class);
    }
}
