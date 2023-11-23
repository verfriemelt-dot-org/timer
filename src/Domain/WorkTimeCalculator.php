<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTime;
use DateTimeImmutable;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Repository\HolidayRepository;

class WorkTimeCalculator
{
    public function __construct(
        private readonly HolidayRepository $holidayRepository,
    ) {}

    public function getTotalWorkHours(EntryListDto $entryListDto): float
    {
        $total = 0;

        foreach ($entryListDto->entries as $entry) {
            if ($entry->type !== EntryType::Work) {
                continue;
            }

            assert(isset($entry->workTime->from, $entry->workTime->till));

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
            if ($entry->type === EntryType::Vacation) {
                $total += 8;
            }
        }

        return $total;
    }

    public function getSickHours(EntryListDto $entryListDto): float
    {
        $total = 0;

        foreach ($entryListDto->entries as $entry) {
            if ($entry->type === EntryType::Sick) {
                $total += 8;
            }
        }

        return $total;
    }

    public function expectedHours(DateTime $day): float
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
