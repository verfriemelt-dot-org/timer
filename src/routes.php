<?php

declare(strict_types=1);

namespace timer;

use DateTime;
use timer\Controller\ExportCsv;
use timer\Controller\PrintMonth;
use timer\Controller\PrintYear;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use verfriemelt\wrapped\_\Router\Route;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;

$routes = [];

$routes[] = Route::create('export')->call(ExportCsv::class);
$routes[] = Route::create('print year')->call(PrintYear::class);
$routes[] = Route::create('print ?(?<month>[0-9]{1,2})?')->call(PrintMonth::class);
$routes[] = Route::create('reset')->call(function () {
    $path = \dirname(__FILE__, 2) . '/data/current.json';
    if (!\file_exists($path)) {
        echo 'not started' . PHP_EOL;
        return;
    }
    var_dump(\file_get_contents($path));
    echo 'deleted' . PHP_EOL;

    unlink($path);
});
$routes[] = Route::create('cat')->call(function () {
    $path = \dirname(__FILE__, 2) . '/data/current.json';
    if (!\file_exists($path)) {
        echo 'not started' . PHP_EOL;
        return;
    }
    var_dump(\file_get_contents($path));
});
$routes[] = Route::create('sick')->call(function (EntryRepository $entryRepository) {
    $entryRepository->add(
        new EntryDto(
            new DateDto((new DateTime())->format('Y-m-d')),
            type: EntryType::Sick,
        )
    );
});
$routes[] = Route::create('vacation')->call(function (EntryRepository $entryRepository) {
    $entryRepository->add(
        new EntryDto(
            new DateDto((new DateTime())->format('Y-m-d')),
            type: EntryType::Sick,
        )
    );
});
$routes[] = Route::create('.*')->call(function (EntryRepository $entryRepository) {
    $path = \dirname(__FILE__, 2) . '/data/current.json';

    if (!\file_exists($path)) {
        $json = (new JsonEncoder())->serialize(new WorkTimeDto((new DateTime())->format('Y-m-d H:i:s')));
        \file_put_contents($path, $json);

        var_dump($json);
        return;
    }

    $json = \file_get_contents($path);
    assert(\is_string($json));

    $dto = (new JsonEncoder())->deserialize($json, WorkTimeDto::class);
    $dto = $dto->till((new DateTime())->format('Y-m-d H:i:s'));

    $work = new EntryDto(
        new DateDto((new DateTime())->format('Y-m-d')),
        $dto
    );

    var_dump($work);

    $entryRepository->add($work);
    \unlink($path);
});

return $routes;
