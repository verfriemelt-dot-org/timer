<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Dto\HolidayListDto;
use timer\Domain\Repository\HolidayRepository;
use Override;

final class HolidayMemoryRepository implements HolidayRepository
{
    private HolidayListDto $list;

    public function __construct(
    ) {
        $this->list = new HolidayListDto();
    }

    #[Override]
    public function all(): HolidayListDto
    {
        return $this->list;
    }

    #[Override]
    public function add(HolidayDto $holiday): void
    {
        $this->list = new HolidayListDto(
            ...$this->all()->holidays,
            ...[$holiday],
        );
    }

    #[Override]
    public function getHoliday(DateTimeImmutable $day): ?HolidayDto
    {
        $dayString = $day->format('Y-m-d');

        foreach ($this->all()->holidays as $holiday) {
            if ($holiday->date->day === $dayString) {
                return $holiday;
            }
        }

        return null;
    }

    #[Override]
    public function getByYear(string $year): HolidayListDto
    {
        return new HolidayListDto(
            ...\array_filter(
                $this->all()->holidays,
                static fn (HolidayDto $dto): bool => str_starts_with($dto->date->day, $year)
            )
        );
    }
}
