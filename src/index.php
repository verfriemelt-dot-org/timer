<?php

declare(strict_types=1);

namespace timer;

use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Http\Request\Request;
use verfriemelt\wrapped\_\Kernel;
use verfriemelt\wrapped\_\View\View;

define('_', true);

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Berlin');

$kernel = new class () extends Kernel {
    public function getProjectPath(): string
    {
        return \dirname(__DIR__);
    }
};

// this needs to be dropped
View::setContainer($kernel->getContainer());

$kernel->loadRoutes(require_once __DIR__ . '/routes.php');
$request = Request::createFromGlobals();
$request->server()->override(
    'REQUEST_URI',
    Console::getInstance()->getArgvAsString()
);
$kernel->handle($request);
