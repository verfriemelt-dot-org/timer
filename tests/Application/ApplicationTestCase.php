<?php

declare(strict_types=1);

namespace timer\tests\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\Repository\ExpectedHoursRepositoryInterface;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Repository\MemoryCurrentWorkRepository;
use timer\Repository\MemoryEntryRepository;
use timer\Repository\MemoryExpectedHoursRepository;
use timer\Repository\MemoryHolidayRepository;
use verfriemelt\wrapped\_\AbstractKernel;
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

    protected MemoryHolidayRepository $holidayRepository;
    protected MemoryEntryRepository $entryRepository;
    protected MemoryCurrentWorkRepository $currentWorkRepository;
    protected MemoryExpectedHoursRepository $expectedHoursRepository;

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
        $this->kernel = new class () extends AbstractKernel {
            public function getProjectPath(): string
            {
                return \TEST_ROOT;
            }
        };

        $this->kernel->getContainer()->register(
            HolidayRepositoryInterface::class,
            $this->holidayRepository = new MemoryHolidayRepository()
        );
        $this->kernel->getContainer()->register(
            EntryRepositoryInterface::class,
            $this->entryRepository = new MemoryEntryRepository()
        );
        $this->kernel->getContainer()->register(
            CurrentWorkRepositoryInterface::class,
            $this->currentWorkRepository = new MemoryCurrentWorkRepository()
        );
        $this->kernel->getContainer()->register(
            ClockInterface::class,
            $this->clock = new MockClock(new DateTimeImmutable('2023-12-07 14:32:16'))
        );

        $this->kernel->getContainer()->register(
            ExpectedHoursRepositoryInterface::class,
            $this->expectedHoursRepository = new MemoryExpectedHoursRepository()
        );
    }
}
