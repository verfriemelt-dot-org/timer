<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use DateTimeImmutable;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:text:year')]
final class PrintYearCommand extends AbstractCommand
{
    public function __construct(
        private readonly PrettyPrinter $print
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $this->print->print(new DateTimeImmutable('first day of january this year'), new DateTimeImmutable('Yesterday'));
        return ExitCode::Success;
    }
}
