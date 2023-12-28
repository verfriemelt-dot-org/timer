<?php

declare(strict_types=1);

namespace timer;

use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Repository\CurrentWorkRepository;
use timer\Repository\EntryRepository;
use timer\Repository\HolidayRepository;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\DotEnv\DotEnv;
use RuntimeException;

define('_', true);

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Berlin');

$kernel = new Kernel();

(new DotEnv())->load('.env');

$path = $kernel->getProjectPath() . '/' . ($_ENV['DATA_PATH'] ?? throw new RuntimeException('DATA_PATH is not set'));

$kernel->getContainer()->register(HolidayRepositoryInterface::class, new HolidayRepository($path));
$kernel->getContainer()->register(EntryRepositoryInterface::class, new EntryRepository($path));
$kernel->getContainer()->register(CurrentWorkRepositoryInterface::class, new CurrentWorkRepository($path));

$kernel->loadCommands('src/Commands', 'src/', __NAMESPACE__);

$kernel->execute(new Console());
