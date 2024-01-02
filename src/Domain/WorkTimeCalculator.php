<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;

class WorkTimeCalculator
{
    final public const float EXPECTED_HOURS = 8.0;

    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
        private readonly TimeDiffCalcalator $timeDiff,
    ) {}

    public function getHours(EntryListDto $entryListDto): float
    {
        $total = 0.0;

        foreach ($entryListDto->entries as $entry) {
            if ($entry->type === EntryType::Work) {
                assert($entry->workTime !== null);
                $total += $this->timeDiff->getInHours($entry->workTime);
            }

            $total += $entry->type->getFactor() / 100 * self::EXPECTED_HOURS;
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
