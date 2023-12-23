<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use DateTimeImmutable;
use timer\Domain\TimeBalanceCalculator;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('balance$')]
final readonly class EntryBalanceCommand extends AbstractCommand
{
    public function __construct(
        private TimeBalanceCalculator $timeBalance
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $dto = $this->timeBalance->get(
            new DateTimeImmutable('2023-01-01'),
            new DateTimeImmutable('Yesterday')
        );

        $console->writeLn("{$dto->actual} // {$dto->expected}");

        return ExitCode::Success;
    }
}
