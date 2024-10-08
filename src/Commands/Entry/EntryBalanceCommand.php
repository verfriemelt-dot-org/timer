<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Clock;
use timer\Domain\Repository\ExpectedHoursRepository;
use timer\Domain\TimeBalanceCalculator;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('balance', 'prints out the current overtime balance')]
final class EntryBalanceCommand extends AbstractCommand
{
    public function __construct(
        private readonly TimeBalanceCalculator $timeBalance,
        private readonly ExpectedHoursRepository $expectedHoursRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $dto = $this->timeBalance->get(
            $this->clock->fromString($this->expectedHoursRepository->all()->hours[0]->from->day),
            $this->clock->fromString('Yesterday'),
        );

        $total = ($dto->actual - $dto->expected > 0 ? '+' : '') . ($dto->actual - $dto->expected);

        $output->writeLn("{$dto->actual} // {$dto->expected} ({$total})");

        return ExitCode::Success;
    }
}
