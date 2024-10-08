<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use timer\Domain\Dto\TimeBalanceDto;
use timer\Domain\Repository\EntryRepository;

/**
 * calculates the current for a given day from the repository
 */
final readonly class TimeBalanceCalculator
{
    public function __construct(
        private EntryRepository $entryRepository,
        private WorkTimeCalculator $workTimeCalculator,
    ) {}

    public function get(DateTimeImmutable $start, DateTimeImmutable $end): TimeBalanceDto
    {
        $total = 0;
        $totalRequired = 0;

        $current = $start;

        while ($current <= $end) {
            $entries = $this->entryRepository->getDay($current);
            $workPerDay = $this->workTimeCalculator->getHours($entries);

            $total += $workPerDay;
            $totalRequired += $this->workTimeCalculator->expectedHours($current);

            $current = $current->modify('+1day');
        }

        return new TimeBalanceDto($total, $totalRequired);
    }
}
