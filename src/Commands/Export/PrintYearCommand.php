<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use timer\Domain\Clock;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgumentMissingException;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:text:year', 'prints the current year')]
final class PrintYearCommand extends AbstractCommand
{
    public function __construct(
        private readonly PrettyPrinter $print,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('year', Argument::OPTIONAL, default: $this->clock->now()->format('Y')));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $year = $input->getArgument('year')->get() ?? throw new ArgumentMissingException();
        $now = $this->clock->fromString("01-01-{$year}");

        $this->print->print(
            $output,
            $now,
            $now->modify('last day of december'),
        );
        return ExitCode::Success;
    }
}
