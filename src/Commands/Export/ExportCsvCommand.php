<?php namespace timer\Commands\Export;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Print\CsvPrinter;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;

use function usort;

#[Command("export:csv")]
final readonly class ExportCsvCommand extends AbstractCommand
{
    public function __construct(
        private readonly CsvPrinter $print

    ) {

    }

    public function execute(Console $console): ExitCode
    {
        $this->print->print(
            new DateTimeImmutable('first day of january this year'),
            new DateTimeImmutable('Today'),
        );

        return ExitCode::Success;
    }
}
