<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Holiday;

use DateTimeImmutable;
use timer\Commands\Holiday\HolidayAddCommand;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class HolidayAddCommandTest extends ApplicationTestCase
{
    public function test(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(HolidayAddCommand::class, ['2024-01-01', 'Neujahr']));

        $repository = $this->kernel->getContainer()->get(HolidayRepositoryInterface::class);

        static::assertInstanceOf(HolidayRepositoryInterface::class, $repository);
        static::assertTrue($repository->isHoliday(new DateTimeImmutable('2024-01-01')));
    }
}
