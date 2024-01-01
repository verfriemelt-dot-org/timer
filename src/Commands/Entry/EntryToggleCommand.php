<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('toggle', 'start and stops new entries and adds them to the repository')]
final class EntryToggleCommand extends AbstractCommand
{
    private Argument $time;

    public function __construct(
        private readonly CurrentWorkRepositoryInterface $currentWorkRepository,
        private readonly EntryRepositoryInterface $entryRepository,
        private readonly Clock $clock
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $this->time = new Argument('time', Argument::VARIADIC);
        $argv->addArguments($this->time);
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        if ($this->time->present()) {
            $time = $this->clock->fromString($this->time->get());
        } else {
            $time = $this->clock->now();
        }

        if (!$this->currentWorkRepository->has()) {
            $output->writeLn(\print_r($this->currentWorkRepository->toggle($time), true));
            return ExitCode::Success;
        }

        $workTimeDto = $this->currentWorkRepository->toggle($time);

        $work = new EntryDto(
            new DateDto($time->format('Y-m-d')),
            $workTimeDto
        );

        $this->entryRepository->add($work);
        $output->writeLn(\print_r($work, true));

        return ExitCode::Success;
    }
}
