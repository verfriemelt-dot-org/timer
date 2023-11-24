<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class TimeBalanceDto
{
    public function __construct(
        public float $actual,
        public float $expected,
    ) {}
}
