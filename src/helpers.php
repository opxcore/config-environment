<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $env = Environment::getEnvironment();

        if (!array_key_exists($key, $env)) {
            if (is_callable($default)) {
                $value = $default();
            } else {
                $value = $default;
            }
        } else {
            $value = $env[$key];
        }

        return $value;
    }
}