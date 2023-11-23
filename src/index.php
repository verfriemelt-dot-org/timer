<?php

declare(strict_types=1);

namespace timer;

use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Repository\CurrentWorkRepository;
use timer\Repository\EntryRepository;
use timer\Repository\HolidayRepository;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Http\Request\Request;
use verfriemelt\wrapped\_\Kernel;

define('_', true);

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Berlin');

$kernel = new class () extends Kernel {
    public function getProjectPath(): string
    {
        return \dirname(__DIR__);
    }
};

$kernel->getContainer()->register(HolidayRepositoryInterface::class, new HolidayRepository());
$kernel->getContainer()->register(EntryRepositoryInterface::class, new EntryRepository());
$kernel->getContainer()->register(CurrentWorkRepository::class, new CurrentWorkRepository());

$kernel->loadRoutes(require_once __DIR__ . '/routes.php');
$request = Request::createFromGlobals();
$request->server()->override(
    'REQUEST_URI',
    Console::getInstance()->getArgvAsString()
);
$kernel->handle($request);
