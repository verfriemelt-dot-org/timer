<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTime;
use timer\Repository\EntryRepository;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Response\Response;

class ExportCsv extends Controller
{
    public function __construct(
        private EntryRepository $entryRepository
    ) {}

    public function handle_index(): Response
    {
        $d = new DateTime('2023-01-01');
        $till = new DateTime('Today');

        while ($d <= $till) {
            $entries = $this->entryRepository->getDay($d);

            foreach ($entries->entries as $dto) {
                echo "{$dto->type->value};{$dto->date->day};{$dto->workTime?->from};{$dto->workTime?->till}";
                echo \PHP_EOL;
            }

            $d->modify('+1 day');
        }
        return new Response();
    }
}
