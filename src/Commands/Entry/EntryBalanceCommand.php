<?php namespace timer\Commands\Entry;

use DateTimeImmutable;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Domain\TimeBalanceCalculator;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use timer\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;

use verfriemelt\wrapped\_\Http\Response\Response;

use function usort;
use function var_dump;

#[Command("balance$")]
final readonly class EntryBalanceCommand extends AbstractCommand
{
    public function __construct(
        private TimeBalanceCalculator $timeBalance
    ) {

    }

    public function execute(Console $console): ExitCode
    {
        $dto = $this->timeBalance->get(
            new DateTimeImmutable('2023-01-01'),
            new DateTimeImmutable('Yesterday')
        );

        $console->writeLn("{$dto->actual} // {$dto->expected}");


        return ExitCode::Success;
    }
}
