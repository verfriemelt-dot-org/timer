<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Dto\PublicHolidayListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;

final class MemoryHolidayRepository implements HolidayRepositoryInterface
{
    private PublicHolidayListDto $list;

    public function __construct(
    ) {
        $this->list = new PublicHolidayListDto();
    }

    public function all(): PublicHolidayListDto
    {
        return $this->list;
    }

    public function add(PublicHolidayDto $publicHoliday): void
    {
        $this->list = new PublicHolidayListDto(
            ...$this->all()->holidays,
            ...[$publicHoliday],
        );
    }

    public function isHoliday(DateTimeImmutable $day): bool
    {
        $holidays = \array_map(fn (PublicHolidayDto $holiday): string => $holiday->date->day, $this->all()->holidays);

        return \in_array($day->format('Y-m-d'), $holidays, true);
    }
}
