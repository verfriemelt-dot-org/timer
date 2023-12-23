<?php

declare(strict_types=1);

namespace tests\Commands;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use timer\Commands\Holiday\HolidayListCommand;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Dto\PublicHolidayListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\AbstractKernel;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\ExitCode;
use verfriemelt\wrapped\_\Kernel\KernelInterface;
use Override;

final class HolidayListCommandTest extends TestCase
{
    private KernelInterface $kernel;

    #[Override]
    public function setUp(): void
    {
        $this->kernel = new class () extends AbstractKernel {
            public function getProjectPath(): string
            {
                return TEST_ROOT;
            }
        };

        $this->kernel->getContainer()->register(
            HolidayRepositoryInterface::class,
            new class () implements HolidayRepositoryInterface {
                public function all(): PublicHolidayListDto
                {
                    return new PublicHolidayListDto();
                }

                public function add(PublicHoliday $publicHoliday): void {}

                public function isHoliday(DateTimeImmutable $day): bool
                {
                    return true;
                }
            }
        );
    }

    public function test(): void
    {
        $command = $this->kernel->getContainer()->get(HolidayListCommand::class);
        static::assertSame(ExitCode::Success, $command->execute(new Console()));
    }
}
