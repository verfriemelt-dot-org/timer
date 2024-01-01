<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use timer\Domain\Clock;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:text:month', 'prints the given month')]
final class PrintMonthCommand extends AbstractCommand
{
    private Argument $year;
    private Argument $month;

    public function __construct(
        private readonly PrettyPrinter $print,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(ArgvParser $parser): void
    {
        $this->month = new Argument('month', Argument::OPTIONAL);
        $this->year = new Argument('year', Argument::OPTIONAL);

        $parser->addArguments($this->month, $this->year);
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $month = (int) ($this->month->get() ?? $this->clock->now()->format('m'));
        $year = (int) ($this->year->get() ?? $this->clock->now()->format('Y'));

        $today = $this->clock->now();
        $start = $this->clock->fromString("{$year}-{$month}-01");
        $end = $start->modify('last day of this month');

        if (!$this->month->present() && $start < $today && $end > $today) {
            $end = $this->clock->fromString('yesterday');
        }

        $this->print->print(
            $output,
            $start,
            $end
        );

        return ExitCode::Success;
    }
}
