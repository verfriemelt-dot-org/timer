<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('reset', 'discards the current worktime entry')]
final class EntryResetCommand extends AbstractCommand
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

        $this->currentWorkRepository->reset();
        $output->writeLn('deleted');

        return ExitCode::Success;
    }
}
