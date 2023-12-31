<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use DateTimeImmutable;
use timer\Domain\Print\CsvPrinter;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:csv', 'exports the current year as a csv')]
final class ExportCsvCommand extends AbstractCommand
{
    public function __construct(
        private readonly CsvPrinter $print
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $this->print->print(
            $console,
            new DateTimeImmutable('first day of january this year'),
            new DateTimeImmutable('Today'),
        );

        return ExitCode::Success;
    }
}
