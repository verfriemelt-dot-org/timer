<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTimeImmutable;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Request\Request;
use verfriemelt\wrapped\_\Http\Response\Response;

class PrintYear extends Controller
{
    public function __construct(
        private readonly PrettyPrinter $print,
    ) {}

    public function handle_index(
        Request $request
    ): Response {
        $this->print->print(new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('Yesterday'));
        return new Response();
    }
}
