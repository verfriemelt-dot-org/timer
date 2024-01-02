<?php

declare(strict_types=1);

namespace timer\Commands\Vacation;

use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('vacation:list', 'prints out all vacation days')]
final class VacationListCommand extends AbstractCommand
{
    public function __construct(
        private readonly EntryRepositoryInterface $entryRepository
    ) {}

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        foreach ($this->entryRepository->getByType(... EntryType::VACATION)->entries as $dto) {
            $output->writeLn("{$dto->date->day} {$dto->type->value}");
        }

        return ExitCode::Success;
    }
}
