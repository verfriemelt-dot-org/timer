<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class HolidayDto
{
    public function __construct(
        public DateDto $date,
        public string $name,
        public int $factor = 100,
    ) {}
}
