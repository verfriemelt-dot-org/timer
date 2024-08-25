<?php

declare(strict_types=1);

namespace timer;

use Override;
use Psr\Clock\ClockInterface;
use RuntimeException;
use timer\Domain\Clock;
use timer\Domain\EventSubscriber\CheckForInitListener;
use timer\Domain\Repository\CurrentWorkRepository;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\ExpectedHoursRepository;
use timer\Domain\Repository\HolidayRepository;
use timer\Repository\CurrentWorkJsonRepository;
use timer\Repository\EntryJsonRepository;
use timer\Repository\ExpectedHoursJsonRepository;
use timer\Repository\HolidayJsonRepository;
use verfriemelt\wrapped\_\Clock\SystemClock;
use verfriemelt\wrapped\_\Kernel\AbstractKernel;

class Kernel extends AbstractKernel
{
    #[Override]
    public function boot(): static
    {
        $dataPath = $_ENV['DATA_PATH'] ?? throw new RuntimeException('DATA_PATH is not set');
        \assert(\is_string($dataPath));

        $path = "{$this->getProjectPath()}/{$dataPath}";

        $this->getContainer()->register(ClockInterface::class, new SystemClock());
        $this->getContainer()->register(HolidayRepository::class, new HolidayJsonRepository($path . '/holidays.json'));
        $this->getContainer()->register(EntryRepository::class, new EntryJsonRepository($path . '/entries.json', $this->getContainer()->get(Clock::class)));
        $this->getContainer()->register(CurrentWorkRepository::class, new CurrentWorkJsonRepository($path . '/current.json'));
        $this->getContainer()->register(ExpectedHoursRepository::class, new ExpectedHoursJsonRepository($path . '/hours.json', $this->getContainer()->get(Clock::class)));

        $this->loadCommands('src/Commands', 'src/', __NAMESPACE__);

        $this->eventDispatcher->addSubscriber($this->getContainer()->get(CheckForInitListener::class));

        return parent::boot();
    }

    #[Override]
    public function getProjectPath(): string
    {
        return \dirname(__DIR__);
    }
}
