<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;
use verfriemelt\wrapped\_\Clock\SystemClock;

final readonly class Clock
{
    public function __construct(
        private ClockInterface $clock = new SystemClock()
    ) {}

    public function now(): DateTimeImmutable
    {
        return $this->clock->now();
    }

    public function fromString(string $string): DateTimeImmutable
    {
        return $this->clock->now()->modify($string);
    }
}
