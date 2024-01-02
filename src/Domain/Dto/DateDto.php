<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

use Exception;
use RuntimeException;
use timer\Domain\Clock;
use verfriemelt\wrapped\_\Clock\SystemClock;

final readonly class DateDto
{
    public function __construct(
        public string $day,
    ) {
        try {
            (new Clock(new SystemClock()))->fromString($day);
        } catch (Exception) {
            throw new RuntimeException("illegal date provided: {$this->day}");
        }
    }
}
