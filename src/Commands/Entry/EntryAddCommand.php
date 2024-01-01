<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;
use RuntimeException;

#[Command('add', 'used to add non-work entries')]
final class EntryAddCommand extends AbstractCommand
{
    private Argument $typeArgument;
    private Argument $dateArgument;

    public function __construct(
        private readonly EntryRepositoryInterface $entryRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $this->typeArgument = new Argument('type');
        $this->dateArgument = new Argument('date', Argument::OPTIONAL);
        $argv->addArguments($this->typeArgument, $this->dateArgument);
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        if ($this->dateArgument->present()) {
            $time = $this->clock->fromString($this->dateArgument->get());
        } else {
            $time = $this->clock->now();
        }

        match ($this->typeArgument->get() ?? '') {
            EntryType::Sick->value => $this->entryRepository->add(
                new EntryDto(
                    new DateDto($time->format('Y-m-d')),
                    type: EntryType::Sick,
                )
            ),
            EntryType::Vacation->value => $this->entryRepository->add(
                new EntryDto(
                    new DateDto($time->format('Y-m-d')),
                    type: EntryType::Vacation,
                )
            ),
            default => throw new RuntimeException('missing or invalid argument')
        };

        return ExitCode::Success;
    }
}
