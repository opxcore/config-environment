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

class SetEnvSafeTest extends TestCase
{
    use ChecksTrait;

    public function testSetEnvStringSafe(): void
    {
        $this->checkSafe('test', 'test');
    }

    public function testSetEnvIntSafe(): void
    {
        $this->checkSafe('test', true, false);
    }

    public function testSetEnvBoolSafe(): void
    {
        $this->checkSafe('test', 42, 0);
    }

    public function testSetEnvNullSafe(): void
    {
        $this->checkSafe('test', 42, null);
    }
}
