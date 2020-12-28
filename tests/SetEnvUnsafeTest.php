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

class SetEnvUnsafeTest extends TestCase
{
    use ChecksTrait;

    public function testSetEnvString(): void
    {
        $this->checkUnsafe('test', 'test');
    }

    public function testSetEnvInt(): void
    {
        $this->checkUnsafe('test', 123);
    }

    public function testSetEnvBool(): void
    {
        $this->checkUnsafe('test', true);
    }

    public function testSetEnvNull(): void
    {
        $this->checkUnsafe('test', null);
    }

    public function testSetEnvArray(): void
    {
        $this->checkUnsafe('test', [1, 2, 3]);
    }
}
