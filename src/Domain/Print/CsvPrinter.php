<?php

declare(strict_types=1);

namespace timer\Domain\Print;

use DateTimeImmutable;
use timer\Domain\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\OutputInterface;

final readonly class CsvPrinter
{
    public function __construct(
        private EntryRepository $entryRepository,
    ) {}

    public function print(
        OutputInterface $console,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
    ): void {
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
