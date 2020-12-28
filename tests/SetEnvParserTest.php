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

class SetEnvParserTest extends TestCase
{
    use ChecksTrait;

    public function testSetEnvString(): void
    {
        $this->checkValue('test', 'test', 'test');
    }

    public function testSetEnvStringQuoted(): void
    {
        $this->checkValue('test', '""', '');
        $this->checkValue('test', ' "42" ', '42');
        $this->checkValue('test', "\t\"42\"\n", '42');
        $this->checkValue('test', '"[42,43]"', '[42,43]');
        $this->checkValue('test', "\"  42\t\"", "  42\t");
        $this->checkValue('test', "\"\t 42 \n\"", "\t 42 \n");
    }

    public function testSetEnvInt(): void
    {
        $this->checkValue('test', '42', 42);
        $this->checkValue('test', '-42', -42);
        $this->checkValue('test', '42.01', 42.01);
        $this->checkValue('test', '-42.01', -42.01);
        $this->checkValue('test', '0.23E+2', 23);
        $this->checkValue('test', '0.15E-2', 0.0015);
    }

    public function testSetEnvBool(): void
    {
        $this->checkValue('test', 'true', true);
        $this->checkValue('test', 'false', false);
    }

    public function testSetEnvNull(): void
    {
        $this->checkValue('test', 'null', null);
    }

    public function testSetEnvArray(): void
    {
        $this->checkValue('test', '[]', []);
        $this->checkValue('test', '[,]', [null, null]);
        $this->checkValue('test', '[1,2,3]', [1, 2, 3]);
        $this->checkValue('test', '[true,false,null]', [true, false, null]);
        $this->checkValue('test', '["1","2","3"]', ['1', '2', '3']);
        $this->checkValue('test', '["1,2,3","2","3"]', ['1,2,3', '2', '3']);
        $this->checkValue('test', '["1,2,3","2",3,null]', ['1,2,3', '2', 3, null]);
    }

    public function testSetEnvStringUnquoted(): void
    {
        $this->checkValue('test', '', null);
        $this->checkValue('test', '42,42', '42,42');
        $this->checkValue('test', ' 42,42', '42,42');
        $this->checkValue('test', 'sante', 'sante');
        $this->checkValue('test', "sante\t", 'sante');
        $this->checkValue('test', "[square ", '[square');
        $this->checkValue('test', "\"quote", '"quote');
    }

    public function testComment(): void
    {
        $this->checkValueNotSet('#test', '42');
        $this->checkValueNotSet('  #test', '42');
    }
}
