<?php

namespace timer\Domain;

use DateTime;
use DateTimeImmutable;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\HolidayRepository;

class WorkTimeCalculator
{

    public function __construct(
        private readonly HolidayRepository $holidayRepository,
        private readonly EntryRepository $entryRepository,
    ) {
    }

    public function getTotalWorkHours(EntryListDto $entryListDto): float
    {
        $total = 0;

        foreach ($entryListDto->entries as $entry) {
            if ($entry->type !== 'work') {
                continue;
            }

            $from = new DateTimeImmutable($entry->workTime->from);
            $to = new DateTimeImmutable($entry->workTime->till);

            $diff = $from->diff($to);

            $total += $diff->h * 3600 + $diff->i * 60 + $diff->s;
        }

        return $total / 3600;
    }

    public function getVacationHours(EntryListDto $entryListDto): float
    {
        $total = 0;
        foreach ($entryListDto->entries as $entry) {
            if ($entry->type === 'vacation') {
                $total += 8;
            }
        }

        return $total;
    }

    public function getSickHours(EntryListDto $entryListDto): float
    {
        $total = 0;

        foreach ($entryListDto->entries as $entry) {
            if ($entry->type === 'sick') {
                $total += 8;
            }
        }

        return $total;
    }

    public function expectedHours(DateTime $day): float
    {
        // weekend
        if (in_array($day->format("N"), ["6", "7"])) {
            return 0;
        }

        if ($this->holidayRepository->isHoliday($day)) {
            return 0;
        }

        return 8;
    }
}
