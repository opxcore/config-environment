<?php

use OpxCore\Config\Environment;

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return  mixed
     */
    function env(string $key, $default = null)
    {
        return Environment::get($key, $default);
    }
}