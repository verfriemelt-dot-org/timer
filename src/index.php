<?php

declare(strict_types=1);

namespace timer;

use Psr\Clock\ClockInterface;
use timer\Domain\Clock;
use timer\Domain\Repository\CurrentWorkRepository;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\ExpectedHoursRepository;
use timer\Domain\Repository\HolidayRepository;
use timer\Repository\CurrentWorkJsonRepository;
use timer\Repository\EntryJsonRepository;
use timer\Repository\ExpectedHoursJsonRepository;
use timer\Repository\HolidayJsonRepository;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Clock\SystemClock;
use verfriemelt\wrapped\_\DotEnv\DotEnv;
use RuntimeException;

define('_', true);

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Berlin');

$kernel = new Kernel();

(new DotEnv())->load('.env');

$path = $kernel->getProjectPath() . '/' . ($_ENV['DATA_PATH'] ?? throw new RuntimeException('DATA_PATH is not set'));

$kernel->getContainer()->register(ClockInterface::class, new SystemClock());
$kernel->getContainer()->register(HolidayRepository::class, new HolidayJsonRepository($path . '/holidays.json'));
$kernel->getContainer()->register(EntryRepository::class, new EntryJsonRepository($path . '/entries.json', $kernel->getContainer()->get(Clock::class)));
$kernel->getContainer()->register(CurrentWorkRepository::class, new CurrentWorkJsonRepository($path . '/current.json'));
$kernel->getContainer()->register(ExpectedHoursRepository::class, new ExpectedHoursJsonRepository($path . '/hours.json', $kernel->getContainer()->get(Clock::class)));

$kernel->loadCommands('src/Commands', 'src/', __NAMESPACE__);
$exitCode = $kernel->execute(new Console());

exit($exitCode->value);
