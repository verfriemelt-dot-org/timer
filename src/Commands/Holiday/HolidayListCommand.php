<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use timer\Domain\Clock;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('holiday:list', 'prints all know holidays')]
final class HolidayListCommand extends AbstractCommand
{
    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
        private readonly Clock $clock
    ) {}

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $holidays = $this->holidayRepository->all()->holidays;
        \usort(
            $holidays,
            fn (PublicHolidayDto $a, PublicHolidayDto $b): int => $this->clock->fromString($a->date->day) <=> $this->clock->fromString($b->date->day)
        );

        foreach ($holidays as $holiday) {
            $output->writeLn("{$holiday->date->day} {$holiday->name}");
        }

        return ExitCode::Success;
    }
}
