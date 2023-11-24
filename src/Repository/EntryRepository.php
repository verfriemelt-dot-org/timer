<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;

class EntryRepository implements EntryRepositoryInterface
{
    private readonly string $path;
    private EntryListDto $list;

    public function __construct()
    {
        $this->path = \dirname(__FILE__, 3) . '/data/entries.json';
    }

    public function all(): EntryListDto
    {
        if (!\file_exists($this->path)) {
            $json = '[]';
        } else {
            $json = \file_get_contents($this->path);
            assert(is_string($json), "cant read {$this->path}");
        }

        return $this->list ??= (new JsonEncoder())->deserialize($json, EntryListDto::class);
    }

    public function add(EntryDto $entry): void
    {
        $this->list = new EntryListDto(
            ...array_values($this->all()->entries),
            ...[$entry],
        );

        $this->write($this->list);
    }

    private function write(EntryListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->entries, true));
    }

    public function getDay(DateTimeImmutable $day): EntryListDto
    {
        return new EntryListDto(
            ...\array_filter(
                $this->all()->entries,
                static fn (EntryDto $dto): bool => $dto->date->day === $day->format('Y-m-d')
            )
        );
    }
}
