<?php

declare(strict_types=1);

namespace timer\Domain\Print;

use DateTimeImmutable;
use timer\Domain\Repository\EntryRepositoryInterface;

final readonly class CsvPrint
{
    public function __construct(
        private EntryRepositoryInterface $entryRepository,
    ) {}

    public function print(DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $current = clone $start;

        while ($current <= $end) {
            $entries = $this->entryRepository->getDay($current);

            foreach ($entries->entries as $dto) {
                echo "{$dto->type->value};{$dto->date->day};{$dto->workTime?->from};{$dto->workTime?->till}";
                echo \PHP_EOL;
            }

            $current = $current->modify('+1 day');
        }
    }
}
