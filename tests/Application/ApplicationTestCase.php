<?php

declare(strict_types=1);

namespace timer\tests\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use timer\Domain\Clock;
use timer\Domain\Repository\CurrentWorkRepository;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\ExpectedHoursRepository;
use timer\Domain\Repository\HolidayRepository;
use timer\Kernel;
use timer\Repository\CurrentWorkMemoryRepository;
use timer\Repository\EntryMemoryRepository;
use timer\Repository\ExpectedHoursMemoryRepository;
use timer\Repository\HolidayMemoryRepository;
use verfriemelt\wrapped\_\Cli\BufferedOutput;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Clock\MockClock;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use verfriemelt\wrapped\_\Kernel\KernelInterface;
use Override;

abstract class ApplicationTestCase extends TestCase
{
    protected MockClock $clock;
    protected KernelInterface $kernel;

    protected BufferedOutput $consoleSpy;

    protected HolidayMemoryRepository $holidayRepository;
    protected EntryMemoryRepository $entryRepository;
    protected CurrentWorkMemoryRepository $currentWorkRepository;
    protected ExpectedHoursMemoryRepository $expectedHoursRepository;

    /**
     * @param class-string<AbstractCommand> $command
     * @param string[]                      $argv
     */
    protected function executeCommand(string $command, array $argv = [], ?Console $cli = null): ExitCode
    {
        $argvParser = new ArgvParser();

        $instance = $this->kernel->getContainer()->get($command);
        $instance->configure($argvParser);
        static::assertInstanceOf($command, $instance);

        $argvParser->parse($argv);

        $this->consoleSpy = new BufferedOutput();

        return $instance->execute($cli ?? $this->consoleSpy);
    }

    #[Override]
    public function setUp(): void
    {
        $this->kernel = new Kernel();
        $this->kernel->getContainer()->register(
            ClockInterface::class,
            $this->clock = new MockClock(new DateTimeImmutable('2023-12-07 14:32:16')),
        );

        $this->kernel->getContainer()->register(
            HolidayRepository::class,
            $this->holidayRepository = new HolidayMemoryRepository(),
        );
        $this->kernel->getContainer()->register(
            EntryRepository::class,
            $this->entryRepository = new EntryMemoryRepository($this->kernel->getContainer()->get(Clock::class)),
        );
        $this->kernel->getContainer()->register(
            CurrentWorkRepository::class,
            $this->currentWorkRepository = new CurrentWorkMemoryRepository(),
        );
        $this->kernel->getContainer()->register(
            ExpectedHoursRepository::class,
            $this->expectedHoursRepository = new ExpectedHoursMemoryRepository(),
        );
    }

    #[Override]
    public function tearDown(): void
    {
        $this->kernel->shutdown();
    }
}
