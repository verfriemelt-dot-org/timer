<?php

declare(strict_types=1);

namespace timer\tests\Application;

use PHPUnit\Framework\TestCase;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Repository\CurrentWorkRepository;
use timer\Repository\EntryRepository;
use timer\Repository\HolidayRepository;
use verfriemelt\wrapped\_\AbstractKernel;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use verfriemelt\wrapped\_\Kernel\KernelInterface;
use Override;

abstract class ApplicationTestCase extends TestCase
{
    protected KernelInterface $kernel;

    /**
     * @param class-string<AbstractCommand> $command
     * @param string[]                      $argv
     */
    protected function executeCommand(string $command, array $argv = []): ExitCode
    {
        $argvParser = new ArgvParser();

        $instance = $this->kernel->getContainer()->get($command);
        $instance->configure($argvParser);
        static::assertInstanceOf($command, $instance);

        $argvParser->parse($argv);
        return $instance->execute(new class () extends Console {
            public function write(string $text, ?int $color = null): static
            {
                return $this;
            }
        });
    }

    #[Override]
    public function setUp(): void
    {
        $path = \TEST_ROOT . '/_data';

        \file_put_contents("{$path}/holidays.json", '[]');
        \file_put_contents("{$path}/entries.json", '[]');
        @unlink("{$path}/current.json");

        $this->kernel = new class () extends AbstractKernel {
            public function getProjectPath(): string
            {
                return \TEST_ROOT;
            }
        };

        $this->kernel->getContainer()->register(HolidayRepositoryInterface::class, new HolidayRepository($path));
        $this->kernel->getContainer()->register(EntryRepositoryInterface::class, new EntryRepository($path));
        $this->kernel->getContainer()->register(CurrentWorkRepositoryInterface::class, new CurrentWorkRepository($path));
    }
}
