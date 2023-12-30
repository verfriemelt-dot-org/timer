<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

use DateTimeImmutable;
use Exception;
use RuntimeException;

final readonly class DateDto
{
    public function __construct(
        public string $day,
    ) {
        try {
            new DateTimeImmutable($day);
        } catch (Exception) {
            throw new RuntimeException("illegal date provided: {$this->day}");
        }
    }
}
