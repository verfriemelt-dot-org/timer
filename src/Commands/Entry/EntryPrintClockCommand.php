<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use Override;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;

#[Command('cat', 'prints out the raw current worktime dto')]
final class EntryPrintClockCommand extends AbstractCommand
{
    public function __construct(
        private readonly CurrentWorkRepositoryInterface $currentWorkRepository,
    ) {}

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        if (!$this->currentWorkRepository->has()) {
            $output->writeLn('not started');
            return ExitCode::Success;
        }

        \var_dump($this->currentWorkRepository->get());

        return ExitCode::Success;
    }
}
