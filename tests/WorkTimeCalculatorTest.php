<?php

declare(strict_types=1);

namespace tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Dto\PublicHolidayListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use Override;

class WorkTimeCalculatorTest extends TestCase
{
    private WorkTimeCalculator $calc;

    #[Override]
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

                public function isHoliday(DateTimeImmutable $day): bool
                {
                    return $day > new DateTimeImmutable('2020-04-01');
                }
            },
            new TimeDiffCalcalator()
        );
    }

    public function test_work_required_on_non_holiday_weekday(): void
    {
        static::assertSame(8.0, $this->calc->expectedHours(new DateTimeImmutable('2020-04-01')));
    }

    public function test_honor_holiday(): void
    {
        static::assertSame(0.0, $this->calc->expectedHours(new DateTimeImmutable('2020-04-02')));
    }

    public function test_honor_weekend(): void
    {
        static::assertSame(0.0, $this->calc->expectedHours(new DateTimeImmutable('2020-03-28')));
        static::assertSame(0.0, $this->calc->expectedHours(new DateTimeImmutable('2020-03-29')));
    }
}
