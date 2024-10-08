<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use Psr\Clock\ClockInterface;
use timer\Domain\Repository\CurrentWorkRepository;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\Attributes\DefaultCommand;
use verfriemelt\wrapped\_\Command\CommandArguments\Option;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[DefaultCommand]
#[Command('print:day', 'the default action; prints the time balance of the current day')]
final class EntryPrintDayCommand extends AbstractCommand
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly WorkTimeCalculator $workTimeCalculator,
        private readonly CurrentWorkRepository $currentWorkRepository,
        private readonly TimeDiffCalcalator $timeDiff,
        private readonly ClockInterface $clock,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addOption(new Option('raw', short: 'r', description: 'just dumpts out the dto'));
    }

    /**
     * @infection-ignore-all
     */
    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $today = $this->clock->now();

        if ($input->getOption('raw')->present()) {
            $this->dumpRaw($output);
            return ExitCode::Success;
        }

        $entries = $this->entryRepository->getDay($today);
        $hours = $this->workTimeCalculator->getHours($entries);

        $expected = $this->workTimeCalculator->expectedHours($today);

        if ($this->currentWorkRepository->has()) {
            $hours += $this->timeDiff->getInHours($this->currentWorkRepository->get()->till($today->format('Y-m-d H:i:s')));
        }

        $hours = \number_format($hours, 2, '.');

        $output->writeLn("[{$hours} :: {$expected}]");

        return ExitCode::Success;
    }

    private function dumpRaw(OutputInterface $output): void
    {
        if (!$this->currentWorkRepository->has()) {
            $output->writeLn('not started');
            return;
        }

        $output->write(print_r($this->currentWorkRepository->get(), true));
    }
}
