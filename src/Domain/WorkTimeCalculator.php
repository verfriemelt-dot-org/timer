<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Repository\ExpectedHoursRepository;
use timer\Domain\Repository\HolidayRepository;
use RuntimeException;

final readonly class WorkTimeCalculator
{
    final public const float EXPECTED_HOURS = 8.0;

    public function __construct(
        private HolidayRepository $holidayRepository,
        private TimeDiffCalcalator $timeDiff,
        private ExpectedHoursRepository $expectedHoursRepository,
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
        $expectedHours = $this->expectedHoursRepository->getActive($day)->hours->toArray()[$day->format('N')] ?? throw new RuntimeException();
        $holiday = $this->holidayRepository->getHoliday($day);

        if ($holiday !== null) {
            return $expectedHours - ($expectedHours / 100 * $holiday->factor);
        }

        return $expectedHours;
    }
}
