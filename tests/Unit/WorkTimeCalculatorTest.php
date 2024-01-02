<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use timer\Repository\MemoryHolidayRepository;
use verfriemelt\wrapped\_\Clock\SystemClock;

class WorkTimeCalculatorTest extends TestCase
{
    private WorkTimeCalculator $calc;

    #[Override]
    public function setUp(): void
    {
        $repo = new MemoryHolidayRepository();
        $repo->add(new PublicHolidayDto(new DateDto('2020-04-02'), 'test'));

        $this->calc = new WorkTimeCalculator(
            $repo,
            new TimeDiffCalcalator(
                new Clock(new SystemClock())
            )
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

    public function test_sick(): void
    {
        $dto = new EntryListDto(
            new EntryDto(
                new DateDto('2023-01-01'),
                type: EntryType::Sick
            )
        );

        static::assertSame((float) 8, $this->calc->getHours($dto));
    }

    public function test_vacation(): void
    {
        $dto = new EntryListDto(
            new EntryDto(
                new DateDto('2023-01-01'),
                type: EntryType::Vacation
            )
        );

        static::assertSame((float) 8, $this->calc->getHours($dto));
    }

    public function test_work(): void
    {
        $dto = new EntryListDto(
            new EntryDto(
                new DateDto('2023-01-01'),
                new WorkTimeDto(
                    '2023-01-01 08:00:00',
                    '2023-01-01 16:00:00',
                ),
                EntryType::Work
            )
        );

        static::assertSame((float) 8, $this->calc->getHours($dto));
    }

    public function test_work_multi(): void
    {
        $dto = new EntryListDto(
            new EntryDto(
                new DateDto('2023-01-01'),
                type: EntryType::Vacation
            ),
            new EntryDto(
                new DateDto('2023-01-01'),
                new WorkTimeDto(
                    '2023-01-01 08:00:00',
                    '2023-01-01 09:00:00',
                ),
                EntryType::Work
            ),
            new EntryDto(
                new DateDto('2023-01-01'),
                new WorkTimeDto(
                    '2023-01-01 10:00:00',
                    '2023-01-01 11:00:00',
                ),
                EntryType::Work
            ),
        );

        static::assertSame((float) 10, $this->calc->getHours($dto));
    }
}
