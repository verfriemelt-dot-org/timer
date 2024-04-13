<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Holiday;

use DateTimeImmutable;
use timer\Commands\Holiday\HolidayAddCommand;
use timer\Domain\Dto\HolidayDto;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class HolidayAddCommandTest extends ApplicationTestCase
{
    public function test(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(HolidayAddCommand::class, ['2024-01-01', 'erster', 'tag', 'im', 'jahr']));

        $holiday = $this->holidayRepository->getHoliday(new DateTimeImmutable('2024-01-01'));

        static::assertInstanceOf(HolidayDto::class, $holiday);
        static::assertSame('erster tag im jahr', $holiday->name);
        static::assertSame(100, $holiday->factor);
    }

    public function test_with_factor(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(HolidayAddCommand::class, ['-f', '50', '2024-01-01', 'Neujahr']),
        );

        $holiday = $this->holidayRepository->getHoliday(new DateTimeImmutable('2024-01-01'));

        static::assertInstanceOf(HolidayDto::class, $holiday);
        static::assertSame(50, $holiday->factor);
    }
}
