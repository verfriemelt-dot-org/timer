<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use Psr\Clock\ClockInterface;
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
        private readonly CsvPrinter $print,
        private readonly ClockInterface $clock,
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $this->print->print(
            $console,
            $this->clock->now()->modify('first day of january'),
            $this->clock->now()
        );

        return ExitCode::Success;
    }
}
