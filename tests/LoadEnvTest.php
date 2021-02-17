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
use OpxCore\Config\Exceptions\EnvironmentException;
use PHPUnit\Framework\TestCase;

class LoadEnvTest extends TestCase
{
    use Env;

    protected string $dir = __DIR__;

    /**
     * @param string $filename
     *
     * @return bool
     * @throws EnvironmentException
     */
    protected function loadEnv(string $filename): bool
    {
        return self::$environment->load('Fixtures' . DIRECTORY_SEPARATOR . $filename);
    }


    /**
     * @throws EnvironmentException
     */
    public function testLoadEnv(): void
    {
        self::assertTrue($this->loadEnv('1.env'));

        self::assertEquals('local', env('APP_ENV'));
        self::assertEquals(null, env('APP_NAME'));
        self::assertEquals('base64:0vqkPYSbwPm3MOzdxQJ76Ps6pouZRjN5xPx3b+dm628=', env('APP_KEY'));
        self::assertEquals(true, env('APP_DEBUG'));
        self::assertEquals('debug', env('APP_LOG_LEVEL'));
        self::assertEquals('127.0.0.1', env('DB_HOST'));
        self::assertEquals(3306, env('DB_PORT'));
        self::assertEquals(null, env('SESSION_DRIVER'));
        self::assertEquals(['log', 'telegram'], env('BROADCAST_DRIVER'));
        self::assertEquals('sync', env('QUEUE_DRIVER'));
        self::assertEquals('smtp.mailtrap.io', env('MAIL_HOST'));
        self::assertEquals('213d0ec150f74d', env('MAIL_USERNAME'));
        self::assertEquals(false, env('ROUTE_AUTO_CACHE'));
    }

    public function testLoadEnvError(): void
    {
        $this->expectException(EnvironmentException::class);
        self::assertTrue($this->loadEnv('2.env'));
    }

    public function testLoadEnvError2(): void
    {
        $this->expectException(EnvironmentException::class);
        self::assertTrue($this->loadEnv('3.env'));
    }

    public function testLoadEnvNoFile(): void
    {
        $this->expectException(EnvironmentException::class);
        self::assertTrue($this->loadEnv('not . env'));
    }

    /**
     * @throws EnvironmentException
     */
    public function testLoadEnvSilent(): void
    {
        self::assertFalse(self::$environment->load(' . env', false, true));
    }

    public function testAutoload():void
    {
        new Environment(__DIR__ .DIRECTORY_SEPARATOR.'Fixtures', '1.env');
        self::assertEquals('local', env('APP_ENV'));
    }

    public function testAutoloadFail():void
    {
        $this->expectException(EnvironmentException::class);

        new Environment(__DIR__ .DIRECTORY_SEPARATOR.'Fixtures', 'no.env');
    }
}
