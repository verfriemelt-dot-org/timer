<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\EntryType;

interface EntryRepository
{
    public function all(): EntryListDto;

    public function add(EntryDto $entry): void;

    public function getDay(DateTimeImmutable $day): EntryListDto;

    public function getByType(EntryType ... $types): EntryListDto;

    public function getByRange(DateTimeImmutable $from, DateTimeImmutable $till, EntryType ... $types): EntryListDto;

    public function initialized(): bool;

    public function initialize(): void;
}
