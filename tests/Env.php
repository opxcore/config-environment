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

trait Env
{
    protected static Environment $environment;

    public static function setUpBeforeClass(): void
    {
        self::$environment = new Environment(__DIR__);
    }

    public static function tearDownAfterClass(): void
    {

    }
}