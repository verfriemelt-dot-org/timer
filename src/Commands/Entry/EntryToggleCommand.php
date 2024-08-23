<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\CurrentWorkRepository;
use timer\Domain\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgumentMissingException;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('toggle', 'start and stops new entries and adds them to the repository')]
final class EntryToggleCommand extends AbstractCommand
{
    public function __construct(
        private readonly CurrentWorkRepository $currentWorkRepository,
        private readonly EntryRepository $entryRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('time', Argument::VARIADIC, default: 'now'));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $time = $this->clock->now()->modify($input->getArgument('time')->get() ?? throw new ArgumentMissingException());

        if (!$this->currentWorkRepository->has()) {
            $output->writeLn(\print_r($this->currentWorkRepository->toggle($time), true));
            return ExitCode::Success;
        }

        $workTimeDto = $this->currentWorkRepository->toggle($time);

        $work = new EntryDto(
            new DateDto($time->format('Y-m-d')),
            EntryType::Work,
            $workTimeDto,
        );

        $this->entryRepository->add($work);
        $output->writeLn(\print_r($work, true));

        return ExitCode::Success;
    }
}
