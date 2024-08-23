<?php

declare(strict_types=1);

namespace timer\Commands\Vacation;

use timer\Domain\Clock;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgumentMissingException;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('vacation:list', 'prints out all vacation days')]
final class VacationListCommand extends AbstractCommand
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('year', Argument::OPTIONAL, default: $this->clock->now()->format('Y')));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $year = $input->getArgument('year')->get() ?? throw new ArgumentMissingException();

        $vacations = $this->entryRepository->getByType(... EntryType::VACATION)->entries;
        $vacations = \array_filter($vacations, static fn (EntryDto $e): bool => \str_starts_with($e->date->day, $year));
        usort($vacations, static fn (EntryDto $a, EntryDto $b): int => \strnatcmp($a->date->day, $b->date->day));

        foreach ($vacations as $dto) {
            $output->writeLn("{$dto->date->day} {$dto->type->value}");
        }

        return ExitCode::Success;
    }
}
