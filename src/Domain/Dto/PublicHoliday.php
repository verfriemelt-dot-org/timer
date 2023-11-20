<?php

namespace timer\Domain\Dto;

final readonly class PublicHoliday
{
    public function __construct(
        public DateDto $date,
        public string $name,
    ) {
    }
}
