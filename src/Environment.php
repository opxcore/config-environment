<?php

namespace OpxCore\Config;

use OpxCore\Config\Exceptions\EnvironmentException;
use OpxCore\Config\Interfaces\EnvironmentInterface;

class Environment implements EnvironmentInterface
{
    /** @var array Set of environment variables */
    protected static array $environment = [];

    /**
     * Load configuration environment.
     *
     * @param string|null $path Path to environment file
     * @param string $filename Environment filename
     * @param bool $safe Skip values if it already set in environment
     * @param bool $silent Whether is Exceptions would be thrown
     *
     * @return  bool
     *
     * @throws EnvironmentException
     */
    public static function load(?string $path = null, string $filename = '.env', bool $safe = false, bool $silent = false): bool
    {
        // If path is null, set path to project root. Typically this file is
        // placed at `project_root/vendor/opxcore/config-environment/src/` folder
        // so dirname(__DIR__, 4) will be `project_root/`
        if ($path === null) {
            $path = dirname(__DIR__, 4);
        }

        return self::loadEnvironment($path . DIRECTORY_SEPARATOR . $filename, $safe, $silent);
    }

    /**
     * Handle environment loading workflow.
     *
     * @param string $file
     * @param bool $safe
     * @param bool $silent
     *
     * @return  bool
     * @throws EnvironmentException
     */
    protected static function loadEnvironment(string $file, bool $safe, bool $silent): bool
    {
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

            self::set($name, $value, $safe);
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
    public static function set(string $key, $value, bool $safe = false): bool
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
            $value = self::parse($value);
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
    protected static function parse(string $value)
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
    public static function get(string $key, $default = null)
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
     * Whether a offset exists.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public static function has($key): bool
    {
        return array_key_exists($key, self::$environment);
    }

    /**
     * Offset to unset.
     *
     * @param mixed $key
     *
     * @return  void
     */
    public static function unset($key): void
    {
        unset(self::$environment[$key]);
    }
}