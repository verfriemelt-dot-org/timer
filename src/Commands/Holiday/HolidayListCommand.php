<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use timer\Domain\Clock;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('holiday:list', 'prints all know holidays')]
final class HolidayListCommand extends AbstractCommand
{
    private Argument $year;

    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
        private readonly Clock $clock
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $argv->addArguments($this->year = new Argument('year', Argument::OPTIONAL));
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $year = $this->year->get();
        $year ??= $this->clock->now()->format('Y');

        $holidays = $this->holidayRepository->getByYear($year)->holidays;
        \usort(
            $holidays,
            fn (HolidayDto $a, HolidayDto $b): int => $this->clock->fromString($a->date->day) <=> $this->clock->fromString($b->date->day)
        );

        foreach ($holidays as $holiday) {
            $output->writeLn("{$holiday->date->day} {$holiday->name}");
        }

        return ExitCode::Success;
    }
}
