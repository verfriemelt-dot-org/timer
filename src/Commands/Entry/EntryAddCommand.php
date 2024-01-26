<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgumentMissingException;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('add', 'used to add non-work entries')]
final class EntryAddCommand extends AbstractCommand
{
    private Argument $typeArgument;
    private Argument $dateArgument;

    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $this->typeArgument = new Argument('type');
        $this->dateArgument = new Argument('date', Argument::OPTIONAL, default: 'now');
        $argv->addArguments($this->typeArgument, $this->dateArgument);
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $time = $this->clock->fromString($this->dateArgument->get() ?? throw new ArgumentMissingException());
        $type = EntryType::from($this->typeArgument->get() ?? '');

        assert($type !== EntryType::Work);

        $this->entryRepository->add(
            new EntryDto(
                new DateDto($time->format('Y-m-d')),
                type: $type,
            )
        );

        return ExitCode::Success;
    }
}
