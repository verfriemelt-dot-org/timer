<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Clock;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use Override;

final class EntryMemoryRepository implements EntryRepository
{
    private EntryListDto $list;

    public function __construct(
        private readonly Clock $clock,
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
                static fn (EntryDto $dto): bool => $dto->date->day === $day->format('Y-m-d'),
            ),
        );
    }

    #[Override]
    public function getByType(EntryType ... $types): EntryListDto
    {
        return new EntryListDto(
            ...\array_filter(
                $this->all()->entries,
                static fn (EntryDto $dto): bool => \in_array($dto->type, $types, true),
            ),
        );
    }

    #[Override]
    public function getByRange(DateTimeImmutable $from, DateTimeImmutable $till, EntryType ... $types): EntryListDto
    {
        $elements = [];

        foreach ($this->all()->entries as $entry) {
            if (\count($types) > 0 && !\in_array($entry->type, $types, true)) {
                continue;
            }

            $date = $this->clock->fromString($entry->date->day);

            if ($date < $from) {
                continue;
            }

            if ($date >= $till) {
                continue;
            }

            $elements[] = $entry;
        }

        return new EntryListDto(... $elements);
    }
}
