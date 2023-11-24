<?php

declare(strict_types=1);

namespace timer;

use timer\Controller\EntryController;
use timer\Controller\ExportCsv;
use timer\Controller\PrintMonth;
use timer\Controller\PrintYear;
use verfriemelt\wrapped\_\Router\Route;

$routes = [];

$routes[] = Route::create('export')->call(ExportCsv::class);
$routes[] = Route::create('print year')->call(PrintYear::class);
$routes[] = Route::create('print ?(?<month>[0-9]{1,2})?')->call(PrintMonth::class);
$routes[] = Route::create('^(cat|sick|vacation|reset|clock|balance)?$')->call(EntryController::class);
$routes[] = Route::create('^(toggle)(?: (?<args>.*))?$')->call(EntryController::class);

return $routes;
