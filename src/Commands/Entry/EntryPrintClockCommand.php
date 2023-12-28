<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('cat')]
final class EntryPrintClockCommand extends AbstractCommand
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

        \var_dump($this->currentWorkRepository->get());

        return ExitCode::Success;
    }
}
