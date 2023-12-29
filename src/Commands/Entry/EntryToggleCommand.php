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
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('toggle')]
final class EntryToggleCommand extends AbstractCommand
{
    private Argument $time;

    public function __construct(
        private readonly CurrentWorkRepositoryInterface $currentWorkRepository,
        private readonly EntryRepositoryInterface $entryRepository,
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $this->time = new Argument('time', Argument::VARIADIC);
        $argv->addArguments($this->time);
    }

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $timeString = $this->time->get() ?? '';

        if (!$this->currentWorkRepository->has()) {
            $console->writeLn(\print_r($this->currentWorkRepository->toggle($timeString), true));
            return ExitCode::Success;
        }

        $workTimeDto = $this->currentWorkRepository->toggle($timeString);

        $work = new EntryDto(
            new DateDto((new DateTimeImmutable($timeString))->format('Y-m-d')),
            $workTimeDto
        );

        $this->entryRepository->add($work);
        $console->writeLn(\print_r($work, true));

        return ExitCode::Success;
    }
}
