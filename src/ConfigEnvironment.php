<?php

namespace OpxCore\Config;

use Dotenv\Dotenv;

class ConfigEnvironment implements \OpxCore\Interfaces\ConfigEnvironmentInterface
{
    /**
     * Load configuration environment.
     *
     * @param  string|null $path
     * @param  string $filename
     *
     * @return  bool
     */
    public static function load($path = null, $filename = '.env'): bool
    {
        // If path is null, set path to project root. Typically this file is
        // placed at `project_root/vendor/opxcore/config-environment/src/` folder
        // so dirname(__DIR__, 4) will be `project_root/`
        if ($path === null) {
            $path = dirname(__DIR__, 4);
        }

        if (!file_exists($path . DIRECTORY_SEPARATOR . $filename)) {

            return false;
        }

        $environment = Dotenv::create($path, $filename);

        $environment->load();

        return true;
    }
}