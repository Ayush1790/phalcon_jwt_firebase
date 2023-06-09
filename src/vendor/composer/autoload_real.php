<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit3eb57c88e1e1166dd13595acc3d7627f
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit3eb57c88e1e1166dd13595acc3d7627f', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit3eb57c88e1e1166dd13595acc3d7627f', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit3eb57c88e1e1166dd13595acc3d7627f::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
