<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Dto\HolidayListDto;

interface HolidayRepositoryInterface
{
    public function all(): HolidayListDto;

    public function add(PublicHolidayDto $publicHoliday): void;

    public function isHoliday(DateTimeImmutable $day): bool;

    public function getByYear(string $year): HolidayListDto;
}
