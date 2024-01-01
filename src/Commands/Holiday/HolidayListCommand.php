<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('holiday:list', 'prints all know holidays')]
final class HolidayListCommand extends AbstractCommand
{
    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
    ) {}

    #[Override]
    public function execute(\verfriemelt\wrapped\_\Cli\OutputInterface $output): ExitCode
    {
        $holidays = $this->holidayRepository->all()->holidays;
        \usort(
            $holidays,
            static fn (PublicHolidayDto $a, PublicHolidayDto $b): int => new DateTimeImmutable($a->date->day) <=> new DateTimeImmutable($b->date->day)
        );

        foreach ($holidays as $holiday) {
            $output->writeLn("{$holiday->date->day} {$holiday->name}");
        }

        return ExitCode::Success;
    }
}
