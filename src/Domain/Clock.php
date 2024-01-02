<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final readonly class Clock
{
    public function __construct(
        private ClockInterface $clock
    ) {}

    public function now(): DateTimeImmutable
    {
        return $this->clock->now();
    }

    public function today(): DateTimeImmutable
    {
        return $this->clock->now()->setTime(0, 0, 0, 0);
    }

    public function fromString(string $string): DateTimeImmutable
    {
        return $this->today()->modify($string);
    }
}
