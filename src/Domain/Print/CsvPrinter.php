<?php

declare(strict_types=1);

namespace timer\Domain\Print;

use DateTimeImmutable;
use timer\Domain\Repository\EntryRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;

final readonly class CsvPrinter
{
    public function __construct(
        private EntryRepositoryInterface $entryRepository,
    ) {}

    public function print(Console $console, DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $current = $start;

        while ($current <= $end) {
            $entries = $this->entryRepository->getDay($current);

            foreach ($entries->entries as $dto) {
                $console->writeLn("{$dto->type->value};{$dto->date->day};{$dto->workTime?->from};{$dto->workTime?->till}");
            }

            $current = $current->modify('+1 day');
        }
    }
}
