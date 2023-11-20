<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTime;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\WorkTimeCalculator;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Request\Request;
use verfriemelt\wrapped\_\Http\Response\Response;

class PrintYear extends Controller
{
    public function __construct(
        private EntryRepository $entryRepository,
        private WorkTimeCalculator $workTimeCalculator,
    ) {}

    public function handle_index(
        Request $request
    ): Response {
        $d = new DateTime('2023-01-01');
        $till = (clone $d)->modify('2023-11-31');

        $total = 0;
        $totalRequired = 0;

        while ($d <= $till) {
            $entries = $this->entryRepository->getDay($d);
            $workPerDay =
                $this->workTimeCalculator->getTotalWorkHours($entries)
                + $this->workTimeCalculator->getVacationHours($entries)
                + $this->workTimeCalculator->getSickHours($entries)
            ;

            $total += $workPerDay;
            $totalRequired += $this->workTimeCalculator->expectedHours($d);

            echo $d->format('Y.m.d l');
            echo " » $workPerDay/{$this->workTimeCalculator->expectedHours($d)}";
            echo ': ' . \PHP_EOL;

            foreach ($entries->entries as $dto) {
                echo "\t";
                echo "{$dto->type}";

                if ($dto->type !== 'work') {
                    echo \PHP_EOL;
                    continue;
                }

                echo ": {$dto->workTime->from} - {$dto->workTime->till}" . \PHP_EOL;
            }

            $d->modify('+1 day');
        }

        echo \PHP_EOL;
        echo "{$total} // {$totalRequired}";
        echo \PHP_EOL;

        return new Response();
    }
}
