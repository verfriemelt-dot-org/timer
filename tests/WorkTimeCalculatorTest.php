<?php

declare(strict_types=1);

namespace tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Dto\PublicHolidayListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Domain\TimeDiff;
use timer\Domain\WorkTimeCalculator;

class WorkTimeCalculatorTest extends TestCase
{
    private WorkTimeCalculator $calc;

    public function setUp(): void
    {
        $this->calc = new WorkTimeCalculator(
            new class () implements HolidayRepositoryInterface {
                public function all(): PublicHolidayListDto
                {
                    return new PublicHolidayListDto();
                }

                public function add(PublicHoliday $publicHoliday): void
                {
                    // TODO: Implement add() method.
                }

                public function isHoliday(DateTime $day): bool
                {
                    return $day < new DateTime('2000-01-01');
                }
            },
            new TimeDiff()
        );
    }

    public function test_expected_hours(): void
    {
        static::assertSame(0.0, $this->calc->expectedHours(new DateTime('1999-01-01')));
        static::assertSame(8.0, $this->calc->expectedHours(new DateTime('2001-01-01')));
    }
}
