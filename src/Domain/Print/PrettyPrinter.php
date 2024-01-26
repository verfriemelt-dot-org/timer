<?php

declare(strict_types=1);

namespace timer\Domain\Print;

use DateTimeImmutable;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\HolidayRepository;
use timer\Domain\TimeBalanceCalculator;
use timer\Domain\WorkTimeCalculator;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Cli\OutputInterface;

final readonly class PrettyPrinter
{
    public function __construct(
        private WorkTimeCalculator $workTimeCalculator,
        private EntryRepository $entryRepository,
        private TimeBalanceCalculator $timeBalance,
        private HolidayRepository $holidayRepository,
    ) {}

    public function print(
        OutputInterface $output,
        DateTimeImmutable $start,
        DateTimeImmutable $end
    ): void {
        $current = $start;

        while ($current <= $end) {
            $entries = $this->entryRepository->getDay($current);
            $workPerDay = $this->workTimeCalculator->getHours($entries);

            $output->write($current->format('Y.m.d l'));

            $this->printHours($output, $workPerDay, $this->workTimeCalculator->expectedHours($current));

            $holiday = $this->holidayRepository->getHoliday($current);
            if ($holiday !== null) {
                $output->write(" » {$holiday->name} ($holiday->factor)");
            }

            $output->eol();

            foreach ($entries->entries as $dto) {
                $output->write('    ');

                if ($dto->type !== EntryType::Work) {
                    $output->write($dto->type->value);
                    $output->eol();
                    continue;
                }

                assert($dto->workTime !== null);
                assert($dto->workTime->till !== null);
                $output->writeLn("{$dto->workTime->from} - {$dto->workTime->till}");
            }

            $current = $current->modify('+1 day');
        }

        $output->eol();
        $balanceDto = $this->timeBalance->get($start, $end);
        $output->writeLn("{$balanceDto->actual} // {$balanceDto->expected}");
    }

    /**
     * @infection-ignore-all
     */
    private function printHours(OutputInterface $output, float $workPerDay, float $expected): void
    {
        $output->write(' » ');
        $output->write(
            "{$workPerDay}/{$expected}",
            ($workPerDay >= $expected) ? Console::STYLE_GREEN : Console::STYLE_RED
        );
    }
}
