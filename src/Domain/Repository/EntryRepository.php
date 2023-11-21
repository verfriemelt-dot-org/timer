<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTime;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;

class EntryRepository extends AbstractRepository
{
    private string $path;

    public function __construct()
    {
        $this->path = \dirname(__FILE__, 4) . '/data/entries.json';
    }

    public function all(): EntryListDto
    {
        if (!\file_exists($this->path)) {
            $json = '[]';
        } else {
            $json = \file_get_contents($this->path);
            assert(is_string($json), "cant read {$this->path}");
        }

        return (new JsonEncoder())->deserialize($json, EntryListDto::class);
    }

    public function truncate(): void
    {
        \file_put_contents($this->path, '[]');
    }

    public function add(EntryDto $entry): void
    {
        $newList = new EntryListDto(
            ...array_values($this->all()->entries),
            ...[$entry],
        );

        $this->write($newList);
    }

    private function write(EntryListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->entries, true));
    }

    public function getDay(DateTime $day): EntryListDto
    {
        return new EntryListDto(
            ...\array_filter(
                $this->all()->entries,
                static fn (EntryDto $dto): bool => $dto->date->day === $day->format('Y-m-d')
            )
        );
    }
}
