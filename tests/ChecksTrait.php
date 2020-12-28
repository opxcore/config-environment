<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Tests\Config\Environment;

use OpxCore\Config\Environment;

trait ChecksTrait
{
    protected function checkSafe($key, $value, $initial = 'initial'): void
    {
        Environment::set($key, $initial);

        $result = Environment::set($key, $value, true);

        self::assertFalse($result);
        self::assertEquals($initial, Environment::get($key));

        Environment::unset($key);
    }

    protected function checkUnsafe($key, $value, $initial = 'initial'): void
    {
        Environment::set($key, $initial);

        $result = Environment::set($key, $value);

        self::assertTrue($result);
        self::assertEquals($value, Environment::get($key));

        Environment::unset($key);
    }

    protected function checkValue($key, $value, $expected): void
    {
        $result = Environment::set($key, $value);

        self::assertTrue($result);
        self::assertEquals($expected, Environment::get($key));

        Environment::unset($key);
    }

    protected function checkValueNotSet($key, $value): void
    {
        Environment::unset($key);

        $result = Environment::set($key, $value);

        self::assertFalse($result);
        self::assertFalse(Environment::has($key));

        Environment::unset($key);
    }
}