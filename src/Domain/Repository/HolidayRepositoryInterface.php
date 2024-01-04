<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Dto\HolidayListDto;

interface HolidayRepositoryInterface
{
    public function all(): HolidayListDto;

    public function add(HolidayDto $holiday): void;

    public function getHoliday(DateTimeImmutable $day): ?HolidayDto;

    public function getByYear(string $year): HolidayListDto;
}
