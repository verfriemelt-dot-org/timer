<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class ExpectedHoursDto
{
    public function __construct(
        public DateDto $from,
        public WorkHoursDto $hours,
    ) {}
}
