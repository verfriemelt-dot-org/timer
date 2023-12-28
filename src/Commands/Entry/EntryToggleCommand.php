<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use DateTimeImmutable;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('toggle')]
final class EntryToggleCommand extends AbstractCommand
{
    public function __construct(
        private readonly CurrentWorkRepositoryInterface $currentWorkRepository,
        private readonly EntryRepositoryInterface $entryRepository,
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $args = array_slice($console->getArgv()->all(), 2);
        $timeString = implode(' ', $args);

        if (!$this->currentWorkRepository->has()) {
            \var_dump($this->currentWorkRepository->toggle($timeString));
            return ExitCode::Success;
        }

        $workTimeDto = $this->currentWorkRepository->toggle($timeString);

        $work = new EntryDto(
            new DateDto((new DateTimeImmutable($timeString))->format('Y-m-d')),
            $workTimeDto
        );

        \var_dump($work);

        $this->entryRepository->add($work);

        return ExitCode::Success;
    }
}
