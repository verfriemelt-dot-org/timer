<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;

class EntryRepository implements EntryRepositoryInterface
{
    private readonly string $path;
    private EntryListDto $list;

    public function __construct(
        string $dataPath
    ) {
        $this->path = "{$dataPath}/entries.json";
    }

    public function all(): EntryListDto
    {
        return $this->list ??= (new JsonEncoder())->deserialize($this->read(), EntryListDto::class);
    }

    public function add(EntryDto $entry): void
    {
        $this->list = new EntryListDto(
            ...array_values($this->all()->entries),
            ...[$entry],
        );

        $this->write($this->list);
    }

    private function read(): string
    {
        if (!\file_exists($this->path)) {
            return '[]';
        }

        /** @phpstan-ignore-next-line */
        return \file_get_contents($this->path) ?: throw new RuntimeException("cant read {$this->path}");
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
