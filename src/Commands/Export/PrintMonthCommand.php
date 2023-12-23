<?php namespace timer\Commands\Export;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Print\CsvPrinter;
use timer\Domain\Print\PrettyPrinter;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;

use function usort;

#[Command("export:text:month")]
final readonly class PrintMonthCommand extends AbstractCommand
{
    public function __construct(
        private readonly PrettyPrinter $print
    ) {

    }

    public function execute(Console $console): ExitCode
    {
        $month = (int) $console->getArgv()->get(2, (new DateTimeImmutable())->format('m'));
        $year = (int) $console->getArgv()->get(3, (new DateTimeImmutable())->format('Y'));

        $today = new DateTimeImmutable();
        $start = new DateTimeImmutable("{$year}-{$month}-01");
        $end = $start->modify('last day of this month');

        if ($console->getArgv()->hasNot(2) && $start < $today && $end > $today) {
            $end = new DateTimeImmutable('yesterday');
        }

        $this->print->print(
            $start,
            $end
        );

        return ExitCode::Success;
    }
}
