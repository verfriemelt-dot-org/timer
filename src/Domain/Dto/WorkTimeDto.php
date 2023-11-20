<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class WorkTimeDto
{
    public function __construct(
        public string $from,
        public ?string $till = null,
    ) {}

    public function till(string $till): WorkTimeDto
    {
        return new self($this->from, $till);
    }
}
