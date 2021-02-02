<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Config;

use OpxCore\Config\Exceptions\EnvironmentException;
use OpxCore\Config\Interfaces\EnvironmentInterface;

class Environment implements EnvironmentInterface
{
    /** @var array Set of environment variables */
    protected static array $environment = [];

    /** @var string Path to environment file */
    protected string $path;

    public function __construct(?string $path = null)
    {
        // If path is null, set path to project root. Typically this file is
        // placed at `project_root/vendor/opxcore/config-environment/src/` folder
        // so dirname(__DIR__, 4) will be `project_root/`
        $this->path = $path ?? dirname(__DIR__, 4);
    }

    /**
     * Load configuration environment.
     *
     * @param string $filename Environment filename
     * @param bool $safe Skip values if it already set in environment
     * @param bool $silent Whether is Exceptions would be thrown
     *
     * @return  bool
     *
     * @throws EnvironmentException
     */
    public function load(string $filename = '.env', bool $safe = false, bool $silent = false): bool
    {
        $file = $this->path . DIRECTORY_SEPARATOR . $filename;

        // Read file content
        $content = @file_get_contents($file);

        if ($content === false) {
            if ($silent) {
                return false;
            }

            throw new EnvironmentException("Can not read file {$file}", 0);
        }

        // Separate content to single lines
        $lines = explode("\n", $content);

        foreach ($lines as $number => $line) {
            // Skip if empty line
            if (trim($line) === '') {
                continue;
            }

            // Split line to variable name and value
            $var = explode('=', $line, 2);

            if (count($var) !== 2 || trim($var[0]) === '') {
                throw new EnvironmentException("Error defining \"{$var[0]}\" in file {$file} line {$number}.", 0);
            }

            [$name, $value] = $var;

            $this->set($name, $value, $safe);
        }

        return true;
    }

    /**
     * Sets variable to environment.
     *
     * @param string $key
     * @param $value
     * @param bool $safe
     *
     * @return  bool
     */
    public function set(string $key, $value, bool $safe = false): bool
    {
        // Check if comment
        if (trim($key, " \r\t\v\0\n")[0] === '#') {
            return false;
        }

        // Check if safe mode is on and self::$environment already has variable
        if ($safe && array_key_exists($key, self::$environment)) {
            return false;
        }

        // Try to parse string values
        if (is_string($value)) {
            $value = $this->parse($value);
        }

        self::$environment[$key] = $value;

        return true;
    }

    /**
     * Parses value from string to typed.
     *
     * @param string $value
     *
     * @return  array|bool|string|null|mixed
     */
    protected function parse(string $value)
    {
        // first remove spaces and control characters
        $value = trim($value, " \r\t\v\0\n");

        // true
        if ($value === 'true') {
            return true;
        }

        // false
        if ($value === 'false') {
            return false;
        }

        // null
        if ($value === 'null') {
            return null;
        }

        $length = strlen($value);

        // quoted string
        if ($length >= 2 && ($value[0] === '"' || $value[0] === '\'') && ($value[$length - 1] === '"' || $value[$length - 1] === '\'')) {
            return trim($value, "\"'");
        }

        // array
        if ($length >= 2 && ($value[0] === '[') && ($value[$length - 1] === ']')) {
            $content = substr($value, 1, -1);

            if ($content === '') {
                return [];
            }

            preg_match_all('/,("[^"]*"|[^,]*)/', ',' . $content, $matches);

            $result = [];

            foreach ($matches[1] as $item) {
                $result[] = self::parse($item);
            }

            return $result;
        }

        // number
        if (($number = filter_var($value, FILTER_VALIDATE_INT)) !== false) {
            return $number;
        }
        if (($number = filter_var($value, FILTER_VALIDATE_FLOAT)) !== false) {
            return $number;
        }

        // otherwise return original string
        return $value;
    }

    /**
     * Gets value from environment.
     *
     * @param string $key
     * @param null|callable|mixed $default
     *
     * @return  array|bool|string|null|mixed
     */
    public function get(string $key, $default = null)
    {
        if (!array_key_exists($key, self::$environment)) {
            if (is_callable($default)) {
                $value = $default();
            } else {
                $value = $default;
            }
        } else {
            $value = self::$environment[$key];
        }

        return $value;
    }

    /**
     * Gets all environment.
     *
     * @return  array
     */
    public static function getEnvironment(): array
    {
        return self::$environment;
    }

    /**
     * Whether a offset exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, self::$environment);
    }

    /**
     * Offset to unset.
     *
     * @param string $key
     *
     * @return  void
     */
    public function unset(string $key): void
    {
        unset(self::$environment[$key]);
    }
}