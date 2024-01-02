<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use DateMalformedStringException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use timer\Domain\Clock;
use verfriemelt\wrapped\_\Clock\MockClock;
use verfriemelt\wrapped\_\Clock\SystemClock;

class ClockTest extends TestCase
{
    public function test_modify(): void
    {
        static::expectException(DateMalformedStringException::class);

        $clock = new Clock(new SystemClock());
        $clock->fromString('foobar');
    }

    public function test_now(): void
    {
        $time = new DateTimeImmutable('2022-04-01');
        $clock = new Clock(new MockClock($time));

        static::assertSame(
            $time->modify('-1 day')->format('Y-m-d'),
            $clock->fromString('yesterday')->format('Y-m-d'),
        );
    }
}
