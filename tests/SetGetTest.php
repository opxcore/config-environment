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

use PHPUnit\Framework\TestCase;

class SetGetTest extends TestCase
{
    public function testGetDefault(): void
    {
        self::assertEquals(42, env('test', 42));
    }

    public function testGetDefaultNull(): void
    {
        self::assertEquals(null, env('test'));
    }

    public function testGetDefaultCallback(): void
    {
        self::assertEquals(3, env('test', static function () {
            return 1 + 4 / 2;
        }));
    }
}
