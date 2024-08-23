<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use timer\Domain\Clock;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\Attributes\Alias;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgumentMissingException;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:text:month', 'prints the given month')]
#[Alias('print')]
final class PrintMonthCommand extends AbstractCommand
{
    public function __construct(
        private readonly PrettyPrinter $print,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('month', Argument::OPTIONAL, default: $this->clock->now()->format('m')));
        $this->addArgument(new Argument('year', Argument::OPTIONAL, default: $this->clock->now()->format('Y')));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $month = $input->getArgument('month')->get() ?? throw new ArgumentMissingException();
        $year = $input->getArgument('year')->get() ?? throw new ArgumentMissingException();

        $today = $this->clock->today();
        $start = $this->clock->fromString("{$year}-{$month}-01");
        $end = $start->modify('last day of this month');

        if (!$input->getArgument('month')->present() && $end > $today) {
            $end = $this->clock->fromString('today');
        }

        $this->print->print(
            $output,
            $start,
            $end,
        );

        return ExitCode::Success;
    }
}
