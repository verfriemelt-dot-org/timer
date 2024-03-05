<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Clock;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;
use Override;

final class EntryJsonRepository implements EntryRepository
{
    private EntryListDto $list;

    /** @var array<string,array<string,EntryListDto>> */
    private array $cache = [];

    public function __construct(
        private readonly string $path,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function all(): EntryListDto
    {
        return $this->list ??= (new JsonEncoder())->deserialize($this->read(), EntryListDto::class);
    }

    #[Override]
    public function add(EntryDto $entry): void
    {
        $this->list = new EntryListDto(
            ...$this->all()->entries,
            ...[$entry],
        );

        $this->write($this->list);
    }

    private function read(): string
    {
        if (!\file_exists($this->path)) {
            return '[]';
        }

        if (!\is_file($this->path)) {
            throw new RuntimeException("cant read {$this->path}, its a directory");
        }

        /** @phpstan-ignore-next-line ignore short ternary */
        return @\file_get_contents($this->path) ?: throw new RuntimeException("cant read {$this->path}");
    }

    private function write(EntryListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->entries, true));
        $this->cache = [];
    }

    #[Override]
    public function getDay(DateTimeImmutable $day): EntryListDto
    {
        $dayString = $day->format('Y-m-d');

        return $this->cache[__METHOD__][$dayString] ??= new EntryListDto(
            ...\array_filter(
                $this->all()->entries,
                static fn (EntryDto $dto): bool => $dto->date->day === $dayString
            )
        );
    }

    #[Override]
    public function getByType(EntryType ... $types): EntryListDto
    {
        return new EntryListDto(
            ...\array_filter(
                $this->all()->entries,
                static fn (EntryDto $dto): bool => in_array($dto->type, $types, true)
            )
        );
    }

    #[Override]
    public function getByRange(DateTimeImmutable $from, DateTimeImmutable $till, EntryType ... $types): EntryListDto
    {
        $elements = [];

        foreach ($this->all()->entries as $entry) {
            if (count($types) > 0 && !in_array($entry->type, $types, true)) {
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
