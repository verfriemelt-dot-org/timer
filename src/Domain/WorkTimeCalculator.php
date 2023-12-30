<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;

class WorkTimeCalculator
{
    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
        private readonly TimeDiffCalcalator $timeDiff,
    ) {}

    public function getWorkHours(EntryListDto $entryListDto): float
    {
        $total = 0;

        foreach ($entryListDto->entries as $entry) {
            if ($entry->type !== EntryType::Work) {
                continue;
            }

            assert($entry->workTime !== null);

            $total += $this->timeDiff->getInSeconds($entry->workTime);
        }

        return $total / 3600;
    }

    public function getVacationHours(EntryListDto $entryListDto): float
    {
        $total = 0;
        foreach ($entryListDto->entries as $entry) {
            if ($entry->type === EntryType::Vacation) {
                $total = 8;
            }
        }

        return $total;
    }

    public function getSickHours(EntryListDto $entryListDto): float
    {
        $total = 0;

        foreach ($entryListDto->entries as $entry) {
            if ($entry->type === EntryType::Sick) {
                $total = 8;
            }
        }

        return $total;
    }

    public function expectedHours(DateTimeImmutable $day): float
    {
        // weekend
        if (in_array($day->format('N'), ['6', '7'], true)) {
            return 0;
        }

        if ($this->holidayRepository->isHoliday($day)) {
            return 0;
        }

        return 8;
    }
}
