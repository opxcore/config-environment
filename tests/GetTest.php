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
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{
    public function testGet(): void
    {
        $env = new Environment();
        self::assertEquals(42, $env->get('test', 42));
        self::assertEquals(null, $env->get('test'));
        self::assertEquals(3, $env->get('test', static function () {
            return 1 + 4 / 2;
        }));
    }
}
