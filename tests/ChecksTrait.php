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
    use Env;

    protected function checkSafe($key, $value, $initial = 'initial'): void
    {
        self::$environment->set($key, $initial);

        $result = self::$environment->set($key, $value, true);

        self::assertFalse($result);
        self::assertEquals($initial, self::$environment->get($key));

        self::$environment->unset($key);
    }

    protected function checkUnsafe($key, $value, $initial = 'initial'): void
    {
        self::$environment->set($key, $initial);

        $result = self::$environment->set($key, $value);

        self::assertTrue($result);
        self::assertEquals($value, self::$environment->get($key));

        self::$environment->unset($key);
    }

    protected function checkValue($key, $value, $expected): void
    {
        $result = self::$environment->set($key, $value);

        self::assertTrue($result);
        self::assertEquals($expected, self::$environment->get($key));

        self::$environment->unset($key);
    }

    protected function checkValueNotSet($key, $value): void
    {
        self::$environment->unset($key);

        $result = self::$environment->set($key, $value);

        self::assertFalse($result);
        self::assertFalse(self::$environment->has($key));

        self::$environment->unset($key);
    }
}