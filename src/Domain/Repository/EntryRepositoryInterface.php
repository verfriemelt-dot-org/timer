<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTime;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;

interface EntryRepositoryInterface
{
    public function all(): EntryListDto;

    public function add(EntryDto $entry): void;

    public function getDay(DateTime $day): EntryListDto;
}
