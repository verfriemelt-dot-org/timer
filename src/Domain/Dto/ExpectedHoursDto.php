<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

class ExpectedHoursDto
{
    public function __construct(
        public DateDto $from,
        public DateDto $till,
        public HoursDto $hours,
    ) {}
}
