<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use DateTimeImmutable;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('$')]
final readonly class EntryPrintDayCommand extends AbstractCommand
{
    public function __construct(
        private EntryRepositoryInterface $entryRepository,
        private WorkTimeCalculator $workTimeCalculator,
        private CurrentWorkRepositoryInterface $currentWorkRepository,
        private TimeDiffCalcalator $timeDiff,
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $today = new DateTimeImmutable();

        $entries = $this->entryRepository->getDay($today);
        $hours = $this->workTimeCalculator->getWorkHours($entries)
            + $this->workTimeCalculator->getVacationHours($entries)
            + $this->workTimeCalculator->getSickHours($entries)
        ;

        $expected = $this->workTimeCalculator->expectedHours($today);

        if ($this->currentWorkRepository->has()) {
            $hours += $this->timeDiff->getInHours($this->currentWorkRepository->get()->till((new DateTimeImmutable())->format('Y-m-d H:i:s')));
        }

        $hours = \number_format($hours, 2, '.');

        $console->writeLn("[{$hours} :: {$expected}]");

        return ExitCode::Success;
    }
}
