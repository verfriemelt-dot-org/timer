<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use timer\Domain\Dto\TimeBalanceDto;
use timer\Domain\Repository\EntryRepositoryInterface;

final readonly class TimeBalance
{
    public function __construct(
        private EntryRepositoryInterface $entryRepository,
        private WorkTimeCalculator $workTimeCalculator,
    ) {}

    public function get(DateTimeImmutable $start, DateTimeImmutable $end): TimeBalanceDto
    {
        $total = 0;
        $totalRequired = 0;

        $current = clone $start;

        while ($current <= $end) {
            $entries = $this->entryRepository->getDay($current);
            $workPerDay =
                $this->workTimeCalculator->getTotalWorkHours($entries)
                + $this->workTimeCalculator->getVacationHours($entries)
                + $this->workTimeCalculator->getSickHours($entries)
            ;

            $total += $workPerDay;
            $totalRequired += $this->workTimeCalculator->expectedHours($current);

            $current = $current->modify('+1day');
        }

        return new TimeBalanceDto($total, $totalRequired);
    }
}
