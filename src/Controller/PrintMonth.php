<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTime;
use timer\Domain\Print\PrettyPrint;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Request\Request;
use verfriemelt\wrapped\_\Http\Response\Response;

class PrintMonth extends Controller
{
    public function __construct(
        private readonly PrettyPrint $print
    ) {}

    public function handle_index(
        Request $request
    ): Response {
        $month = (int) $request->attributes()->get('month', (new DateTime())->format('m'));

        $date = new DateTime("2023-{$month}-01");

        $this->print->print(
            $date,
            (clone $date)->modify('last day of this month'),
        );

        return new Response();
    }
}
