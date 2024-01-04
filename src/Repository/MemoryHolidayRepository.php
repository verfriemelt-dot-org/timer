<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Dto\HolidayListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;

final class MemoryHolidayRepository implements HolidayRepositoryInterface
{
    private HolidayListDto $list;

    public function __construct(
    ) {
        $this->list = new HolidayListDto();
    }

    public function all(): HolidayListDto
    {
        return $this->list;
    }

    public function add(PublicHolidayDto $publicHoliday): void
    {
        $this->list = new HolidayListDto(
            ...$this->all()->holidays,
            ...[$publicHoliday],
        );
    }

    public function isHoliday(DateTimeImmutable $day): bool
    {
        $holidays = \array_map(fn (PublicHolidayDto $holiday): string => $holiday->date->day, $this->all()->holidays);

        return \in_array($day->format('Y-m-d'), $holidays, true);
    }

    public function getByYear(string $year): HolidayListDto
    {
        return new HolidayListDto(
            ...\array_filter(
                $this->all()->holidays,
                static fn (PublicHolidayDto $dto): bool => str_starts_with($dto->date->day, $year)
            )
        );
    }
}
