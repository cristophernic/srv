<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf78225fb95dd5ac2b4f6c6761e163b21
{
    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/application/core',
        1 => __DIR__ . '/../..' . '/application/model',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->fallbackDirsPsr4 = ComposerStaticInitf78225fb95dd5ac2b4f6c6761e163b21::$fallbackDirsPsr4;

        }, null, ClassLoader::class);
    }
}
