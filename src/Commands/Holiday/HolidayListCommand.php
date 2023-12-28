<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('holiday:list')]
final class HolidayListCommand extends AbstractCommand
{
    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $holidays = $this->holidayRepository->all()->holidays;
        \usort(
            $holidays,
            static fn (PublicHoliday $a, PublicHoliday $b): int => new DateTimeImmutable($a->date->day) <=> new DateTimeImmutable($b->date->day)
        );

        foreach ($holidays as $holiday) {
            $console->writeLn("{$holiday->date->day} {$holiday->name}");
        }

        return ExitCode::Success;
    }
}
