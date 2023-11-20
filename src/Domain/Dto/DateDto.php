<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

use Exception;
use Nette\Utils\DateTime;
use RuntimeException;

final readonly class DateDto
{
    public function __construct(
        public string $day,
    ) {
        try {
            new DateTime($day);
        } catch (Exception $e) {
            throw new RuntimeException('illegal date provided: ' . $this->day);
        }
    }
}
