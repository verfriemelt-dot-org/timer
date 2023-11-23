<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

use timer\Domain\EntryType;

class EntryDto
{
    public function __construct(
        public DateDto $date,
        public ?WorkTimeDto $workTime = null,
        public EntryType $type = EntryType::Work,
    ) {}
}
