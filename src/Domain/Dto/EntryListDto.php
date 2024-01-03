<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class EntryListDto
{
    /** @var EntryDto[] */
    public array $entries;

    public function __construct(
        EntryDto ...$entries
    ) {
        $this->entries = $entries;
    }
}
