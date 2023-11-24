<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;

interface EntryRepositoryInterface
{
    public function all(): EntryListDto;

    public function add(EntryDto $entry): void;

    public function getDay(DateTimeImmutable $day): EntryListDto;
}
