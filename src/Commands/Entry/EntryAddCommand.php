<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Alias;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgumentMissingException;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('entry:add', 'used to add non-work entries')]
#[Alias('add')]
final class EntryAddCommand extends AbstractCommand
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('type'));
        $this->addArgument(new Argument('date', Argument::OPTIONAL, default: 'now'));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $time = $this->clock->fromString($input->getArgument('date')->get() ?? throw new ArgumentMissingException());
        $type = EntryType::from($input->getArgument('type')->get() ?? '');

        assert($type !== EntryType::Work);

        $this->entryRepository->add(
            new EntryDto(
                new DateDto($time->format('Y-m-d')),
                type: $type,
            ),
        );

        return ExitCode::Success;
    }
}
