<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTimeImmutable;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Request\Request;
use verfriemelt\wrapped\_\Http\Response\Response;

class PrintMonth extends Controller
{
    public function __construct(
        private readonly PrettyPrinter $print
    ) {}

    public function handle_index(
        Request $request
    ): Response {
        $month = (int) $request->attributes()->get('month', (new DateTimeImmutable())->format('m'));

        $today = new DateTimeImmutable();
        $start = new DateTimeImmutable("2023-{$month}-01");
        $end = $start->modify('last day of this month');

        if ($request->attributes()->hasNot('month') && $start < $today && $end > $today) {
            $end = new DateTimeImmutable('yesterday');
        }

        $this->print->print(
            $start,
            $end
        );

        return new Response();
    }
}
