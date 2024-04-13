<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use timer\Domain\Clock;
use timer\Domain\Print\CsvPrinter;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:csv', 'exports the current year as a csv')]
final class ExportCsvCommand extends AbstractCommand
{
    public function __construct(
        private readonly CsvPrinter $print,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $this->print->print(
            $output,
            $this->clock->today()->modify('first day of january'),
            $this->clock->today(),
        );

        return ExitCode::Success;
    }
}
