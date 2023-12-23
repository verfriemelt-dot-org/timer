<?php namespace timer\Commands\Entry;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Domain\TimeBalanceCalculator;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;

use verfriemelt\wrapped\_\Http\Response\Response;

use function usort;
use function var_dump;

#[Command("cat$")]
final readonly class EntryPrintClockCommand extends AbstractCommand
{
    public function __construct(
        private CurrentWorkRepositoryInterface $currentWorkRepository,
    ) {

    }

    public function execute(Console $console): ExitCode
    {
        if (!$this->currentWorkRepository->has()) {
            $console->writeLn('not started');
            return ExitCode::Success;
        }

        var_dump($this->currentWorkRepository->get());

        return ExitCode::Success;
    }
}
