<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use DateTimeImmutable;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:text:month')]
final class PrintMonthCommand extends AbstractCommand
{
    private Argument $year;
    private Argument $month;

    public function __construct(
        private readonly PrettyPrinter $print,
    ) {}

    #[Override]
    public function configure(ArgvParser $parser): void
    {
        $this->month = new Argument('month', Argument::OPTIONAL);
        $this->year = new Argument('year', Argument::OPTIONAL);

        $parser->addArguments($this->month, $this->year);
    }

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $month = (int) ($this->month->get() ??  (new DateTimeImmutable())->format('m'));
        $year = (int) ($this->year->get() ??  (new DateTimeImmutable())->format('Y'));

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
