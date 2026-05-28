<?php

class Autoloader
{
    private static $directories = [
        'core',
        'controllers',
        'models',
        'helpers',
        'config',
    ];

    /**
     * Enregistrer l'autoloader
     */
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Charger automatiquement les classes
     */
    private static function autoload($class)
    {
        $basePath = __DIR__ . '/../';

        foreach (self::$directories as $directory) {
            $file = $basePath . $directory . '/' . $class . '.php';

            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }

        return false;
    }

    /**
     * Ajouter un répertoire à l'autoloader
     */
    public static function addDirectory($directory)
    {
        self::$directories[] = $directory;
    }
}
