<?php

declare(strict_types=1);

namespace timer\Domain\Print;

use DateTime;
use timer\Domain\EntryType;
use timer\Domain\WorkTimeCalculator;
use timer\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\Console;

final readonly class PrettyPrint
{
    public function __construct(
        private WorkTimeCalculator $workTimeCalculator,
        private EntryRepository $entryRepository,
        private Console $console,
    ) {}

    public function print(DateTime $start, DateTime $end): void
    {
        $total = 0;
        $totalRequired = 0;

        while ($start <= $end) {
            $entries = $this->entryRepository->getDay($start);
            $workPerDay =
                $this->workTimeCalculator->getTotalWorkHours($entries)
                + $this->workTimeCalculator->getVacationHours($entries)
                + $this->workTimeCalculator->getSickHours($entries)
            ;

            $total += $workPerDay;
            $totalRequired += $this->workTimeCalculator->expectedHours($start);

            $this->console->write($start->format('Y.m.d l'));

            $this->printHours($workPerDay, $this->workTimeCalculator->expectedHours($start));
            $this->console->eol();

            foreach ($entries->entries as $dto) {
                $this->console->write('    ');

                if ($dto->type !== EntryType::Work) {
                    $this->console->write($dto->type->value);
                    $this->console->eol();
                    continue;
                }

                $this->console->writeLn("{$dto->workTime?->from} - {$dto->workTime?->till}");
            }

            $start->modify('+1 day');
        }

        $this->console->eol();
        $this->console->writeLn("{$total} // {$totalRequired}");
    }

    private function printHours(float $workPerDay, float $expected): void
    {
        $this->console->write(' » ');
        $this->console->write(
            "{$workPerDay}/{$expected}",
            ($workPerDay >= $expected) ? Console::STYLE_GREEN : Console::STYLE_RED
        );
    }
}
