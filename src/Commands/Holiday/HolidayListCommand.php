<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use timer\Domain\Clock;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Repository\HolidayRepository;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgumentMissingException;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('holiday:list', 'prints all know holidays')]
final class HolidayListCommand extends AbstractCommand
{
    public function __construct(
        private readonly HolidayRepository $holidayRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('year', Argument::OPTIONAL, default: $this->clock->now()->format('Y')));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $year = $input->getArgument('year')->get() ?? throw new ArgumentMissingException();

        $holidays = $this->holidayRepository->getByYear($year)->holidays;
        \usort(
            $holidays,
            fn (HolidayDto $a, HolidayDto $b): int => $this->clock->fromString($a->date->day) <=> $this->clock->fromString($b->date->day),
        );

        foreach ($holidays as $holiday) {
            $output->writeLn("{$holiday->date->day} {$holiday->name}");
        }

        return ExitCode::Success;
    }
}
