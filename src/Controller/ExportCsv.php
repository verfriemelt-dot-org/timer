<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTimeImmutable;
use timer\Domain\Print\CsvPrint;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Response\Response;

class ExportCsv extends Controller
{
    public function __construct(
        private readonly CsvPrint $print
    ) {}

    public function handle_index(): Response
    {
        $this->print->print(
            new DateTimeImmutable('2023-01-01'),
            new DateTimeImmutable('Today'),
        );

        return new Response();
    }
}
