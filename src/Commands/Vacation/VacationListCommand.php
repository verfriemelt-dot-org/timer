<?php

declare(strict_types=1);

namespace timer\Commands\Vacation;

use timer\Domain\Clock;
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

#[Command('vacation:list', 'prints out all vacation days')]
final class VacationListCommand extends AbstractCommand
{
    private Argument $year;

    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $argv->addArguments($this->year = new Argument('year', Argument::OPTIONAL, default: $this->clock->now()->format('Y')));
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $year = $this->year->get() ?? throw new ArgumentMissingException();

        $vacations = $this->entryRepository->getByType(... EntryType::VACATION)->entries;
        $vacations = \array_filter($vacations, static fn (EntryDto $e): bool => \str_starts_with($e->date->day, $year));

        foreach ($vacations as $dto) {
            $output->writeLn("{$dto->date->day} {$dto->type->value}");
        }

        return ExitCode::Success;
    }
}
