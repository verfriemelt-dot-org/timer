<?php

declare(strict_types=1);

namespace timer;

use timer\Controller\PrintMonth;
use timer\Controller\PrintYear;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\HolidayRepository;
use verfriemelt\wrapped\_\Router\Route;
use DateTimeImmutable;

$routes = [];

$routes[] = Route::create('import')->call(function (
    EntryRepository $entryRepository,
) {
    $entryRepository->truncate();
    $fp = fopen(__DIR__ . '/../data/export.csv', 'r');

    while ($data = \fgetcsv($fp)) {
        [$from, $to, $amount, $type] = [$data[0], $data[1], $data[4], $data[7]];

        $date = (new DateTimeImmutable($from))->setTime(0, 0, 0, 0);

        $slices = [];

        if (\in_array($type, ['vacation', 'sick'])) {
            $entryRepository->add(
                new EntryDto(
                    new DateDto($date->format('Y-m-d')),
                    type: $type
                )
            );

            continue;
        }

        if ($amount <= 6) {
            $start = $date->setTime(8, 30, 0, 0)->modify('+' . \mt_rand(-1800, 1800) . 'seconds');
            $end = $date->setTime(8, 30, 0, 0)->modify("+{$amount}hours");

            $slices[] = new EntryDto(
                new DateDto($date->format('Y-m-d')),
                new WorkTimeDto(
                    $start->format('Y-m-d H:i:s'),
                    $end->format('Y-m-d H:i:s'),
                ),
                $type
            );
        } else {
            $firstHalf = (4.5 * 3600 + \mt_rand(-1800, 1800));
            $break = \mt_rand(25 * 60, 66 * 60);
            $secondHalf = $amount * 3600 - 4.5 * 3600 - \mt_rand(-900, 900) - 900;

            $start = $date->setTime(8, 30, 0, 0)->modify('+' . \mt_rand(-600, 900) . 'seconds');
            $end = $date->setTime(8, 30, 0, 0)->modify("+{$firstHalf}seconds");

            $slices[] = new EntryDto(
                new DateDto($date->format('Y-m-d')),
                new WorkTimeDto(
                    $start->format('Y-m-d H:i:s'),
                    $end->format('Y-m-d H:i:s'),
                ),
                $type
            );

            $start = $end->modify("+{$break}seconds");
            $end = $start->modify("+{$break}seconds")->modify("+{$secondHalf}seconds");

            $slices[] = new EntryDto(
                new DateDto($date->format('Y-m-d')),
                new WorkTimeDto(
                    $start->format('Y-m-d H:i:s'),
                    $end->format('Y-m-d H:i:s'),
                ),
                $type
            );
        }

        foreach ($slices as $slice) {
            $entryRepository->add($slice);
        }
    }
});
$routes[] = Route::create('print year')->call(PrintYear::class);
$routes[] = Route::create('print ?(?<month>[0-9]{1,2})?')->call(PrintMonth::class);
$routes[] = Route::create('.*')->call(function (
    HolidayRepository $repo,
) {
    echo 'no action';

    //    $repo->truncate();
    //
    //    $holidays = [
    //        new PublicHoliday(new DateDto('2023-01-01'), 'Neujahrstag'),
    //        new PublicHoliday(new DateDto('2023-04-07'), 'Karfreitag'),
    //        new PublicHoliday(new DateDto('2023-04-10'), 'Ostermontag'),
    //        new PublicHoliday(new DateDto('2023-05-01'), '1. Mai'),
    //        new PublicHoliday(new DateDto('2023-05-18'), 'Christi Himmelfahrt'),
    //        new PublicHoliday(new DateDto('2023-05-29'), 'Pfingstmontag'),
    //        new PublicHoliday(new DateDto('2023-10-03'), 'Tag der Deutschen Einheit'),
    //        new PublicHoliday(new DateDto('2023-10-31'), 'Reformationstag'),
    //        new PublicHoliday(new DateDto('2023-11-22'), 'Buß- und Bettag'),
    //        new PublicHoliday(new DateDto('2023-12-25'), '1. Weihnachtsfeiertag'),
    //        new PublicHoliday(new DateDto('2023-12-26'), '2. Weihnachtsfeiertag'),
    //    ];
    //
    //    foreach($holidays as $hol) {
    //        $repo->add($hol);
    //    }
});

return $routes;
