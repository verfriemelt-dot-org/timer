<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

use timer\Domain\EntryType;

final readonly class EntryDto
{
    public function __construct(
        public DateDto $date,
        public EntryType $type = EntryType::Work,
        public ?WorkTimeDto $workTime = null,
    ) {}
}
