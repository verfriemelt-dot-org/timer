<?php

declare(strict_types=1);

namespace timer\Domain\Print;

use DateTime;
use timer\Repository\EntryRepository;

final readonly class CsvPrint
{
    public function __construct(
        private EntryRepository $entryRepository,
    ) {}

    public function print(DateTime $start, DateTime $end): void
    {
        while ($start <= $end) {
            $entries = $this->entryRepository->getDay($start);

            foreach ($entries->entries as $dto) {
                echo "{$dto->type->value};{$dto->date->day};{$dto->workTime?->from};{$dto->workTime?->till}";
                echo \PHP_EOL;
            }

            $start->modify('+1 day');
        }
    }
}
