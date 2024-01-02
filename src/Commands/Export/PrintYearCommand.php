<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use Psr\Clock\ClockInterface;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:text:year', 'prints the current year')]
final class PrintYearCommand extends AbstractCommand
{
    private Argument $year;

    public function __construct(
        private readonly PrettyPrinter $print,
        private readonly ClockInterface $clock,
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $this->year = new Argument('year', Argument::OPTIONAL);
        $argv->addArguments($this->year);
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $year = (int) ($this->year->get() ?? $this->clock->now()->format('Y'));
        $now = $this->clock->now()->setDate($year, 1, 1);

        $this->print->print(
            $output,
            $now->modify('first day of january'),
            $now->modify('last day of december')
        );
        return ExitCode::Success;
    }
}
