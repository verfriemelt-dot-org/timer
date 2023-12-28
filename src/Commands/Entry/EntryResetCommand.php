<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('reset')]
final class EntryResetCommand extends AbstractCommand
{
    public function __construct(
        private readonly CurrentWorkRepositoryInterface $currentWorkRepository,
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        if (!$this->currentWorkRepository->has()) {
            $console->writeLn('not started');
            return ExitCode::Success;
        }

        $this->currentWorkRepository->reset();
        $console->writeLn('deleted');

        return ExitCode::Success;
    }
}
