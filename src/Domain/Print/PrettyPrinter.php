<?php

declare(strict_types=1);

namespace timer\Domain\Print;

use DateTimeImmutable;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\TimeBalanceCalculator;
use timer\Domain\WorkTimeCalculator;
use verfriemelt\wrapped\_\Cli\Console;

final readonly class PrettyPrinter
{
    public function __construct(
        private WorkTimeCalculator $workTimeCalculator,
        private EntryRepositoryInterface $entryRepository,
        private TimeBalanceCalculator $timeBalance,
        private Console $console,
    ) {}

    public function print(DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $current = clone $start;

        while ($current <= $end) {
            $entries = $this->entryRepository->getDay($current);
            $workPerDay =
                $this->workTimeCalculator->getTotalWorkHours($entries)
                + $this->workTimeCalculator->getVacationHours($entries)
                + $this->workTimeCalculator->getSickHours($entries)
            ;

            $this->console->write($current->format('Y.m.d l'));

            $this->printHours($workPerDay, $this->workTimeCalculator->expectedHours($current));
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

            $current = $current->modify('+1 day');
        }

        $this->console->eol();
        $balanceDto = $this->timeBalance->get($start, $end);
        $this->console->writeLn("{$balanceDto->actual} // {$balanceDto->expected}");
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
