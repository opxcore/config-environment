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
    public function load($path = null, $filename = '.env'): bool
    {
        if($path === null) {
            $path = dirname(__DIR__, 4);
        }

        if(file_exists($path . DIRECTORY_SEPARATOR. $filename)) {
            $environment = Dotenv::create($path, $filename);

            $environment->load();

            return true;
        }

        return false;
    }
}