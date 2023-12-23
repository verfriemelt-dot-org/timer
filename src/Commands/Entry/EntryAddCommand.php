<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use DateTimeImmutable;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;
use RuntimeException;

#[Command('add')]
final readonly class EntryAddCommand extends AbstractCommand
{
    public function __construct(
        private EntryRepositoryInterface $entryRepository,
    ) {}

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $type = $console->getArgv()->get(2, '');
        assert(\is_string($type));
        match ($type) {
            EntryType::Sick->value => $this->entryRepository->add(
                new EntryDto(
                    new DateDto((new DateTimeImmutable())->format('Y-m-d')),
                    type: EntryType::Sick,
                )
            ),
            EntryType::Vacation->value => $this->entryRepository->add(
                new EntryDto(
                    new DateDto((new DateTimeImmutable())->format('Y-m-d')),
                    type: EntryType::Vacation,
                )
            ),
            default => throw new RuntimeException('missing or invalid argument')
        };

        return ExitCode::Success;
    }
}
