<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

class EntryDto
{
    public function __construct(
        public DateDto $date,
        public ?WorkTimeDto $workTime = null,
        public string $type = 'work',
    ) {
        \assert(\in_array($this->type, ['vacation', 'work', 'sick']));
    }
}
