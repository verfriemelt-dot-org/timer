<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

use timer\Domain\Vacation\VacationRuleType;

final readonly class VacationRuleDto
{
    public function __construct(
        public DateDto $validFrom,
        public DateDto $validTill,
        public VacationRuleType $type,
        public float $amount,
    ) {}
}
