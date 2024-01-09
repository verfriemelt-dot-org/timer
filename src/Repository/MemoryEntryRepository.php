<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepositoryInterface;
use Override;

final class MemoryEntryRepository implements EntryRepositoryInterface
{
    private EntryListDto $list;

    public function __construct(
    ) {
        $this->list = new EntryListDto();
    }

    #[Override]
    public function all(): EntryListDto
    {
        return $this->list;
    }

    #[Override]
    public function add(EntryDto $entry): void
    {
        $this->list = new EntryListDto(
            ...$this->all()->entries,
            ...[$entry],
        );
    }

    #[Override]
    public function getDay(DateTimeImmutable $day): EntryListDto
    {
        return new EntryListDto(
            ...\array_filter(
                $this->all()->entries,
                static fn (EntryDto $dto): bool => $dto->date->day === $day->format('Y-m-d')
            )
        );
    }

    #[Override]
    public function getByType(EntryType ... $types): EntryListDto
    {
        return new EntryListDto(
            ...\array_filter(
                $this->all()->entries,
                static fn (EntryDto $dto): bool => \in_array($dto->type, $types, true)
            )
        );
    }
}
