<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class WorkTimeDto
{
    public function __construct(
        public string $from,
        public string $till,
    ) {}
}
