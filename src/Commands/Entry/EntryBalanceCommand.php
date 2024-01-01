<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use DateTimeImmutable;
use timer\Domain\TimeBalanceCalculator;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('balance', 'prints out the current overtime balance')]
final class EntryBalanceCommand extends AbstractCommand
{
    public function __construct(
        private readonly TimeBalanceCalculator $timeBalance
    ) {}

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $dto = $this->timeBalance->get(
            new DateTimeImmutable('2023-01-01'),
            new DateTimeImmutable('Yesterday')
        );

        $output->writeLn("{$dto->actual} // {$dto->expected}");

        return ExitCode::Success;
    }
}
