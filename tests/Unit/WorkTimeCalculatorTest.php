<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\EntryListDto;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use timer\Repository\MemoryExpectedHoursRepository;
use timer\Repository\MemoryHolidayRepository;
use verfriemelt\wrapped\_\Clock\SystemClock;

class WorkTimeCalculatorTest extends TestCase
{
    private WorkTimeCalculator $calc;

    #[Override]
    public function setUp(): void
    {
        $repo = new MemoryHolidayRepository();
        $repo->add(new HolidayDto(new DateDto('2020-04-02'), 'test'));
        $repo->add(new HolidayDto(new DateDto('2020-04-03'), 'test-with-factor', 50));

        $this->calc = new WorkTimeCalculator(
            $repo,
            new TimeDiffCalcalator(
                new Clock(new SystemClock())
            ),
            new MemoryExpectedHoursRepository()
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

    public function test_honor_holiday_factor(): void
    {
        static::assertSame(4.0, $this->calc->expectedHours(new DateTimeImmutable('2020-04-03')));
    }

    public function test_honor_weekend(): void
    {
        static::assertSame(0.0, $this->calc->expectedHours(new DateTimeImmutable('2020-03-28')));
        static::assertSame(0.0, $this->calc->expectedHours(new DateTimeImmutable('2020-03-29')));
    }

    /**
     * @return iterable<array{EntryType, float}>
     */
    public static function typesFactors(): iterable
    {
        yield EntryType::OvertimeReduction->value => [EntryType::OvertimeReduction, 0.0];
        yield EntryType::Sick->value => [EntryType::Sick, 8.0];
        yield EntryType::SickHalf->value => [EntryType::SickHalf, 4.0];
        yield EntryType::Vacation->value => [EntryType::Vacation, 8.0];
        yield EntryType::VacationHalf->value => [EntryType::VacationHalf, 4.0];
        yield EntryType::SpecialVacation->value => [EntryType::SpecialVacation, 8.0];
        yield EntryType::MourningLeave->value => [EntryType::MourningLeave, 8.0];
        yield EntryType::EducationalVacation->value => [EntryType::EducationalVacation, 8.0];
    }

    #[DataProvider('typesFactors')]
    public function test_type_factors(EntryType $type, float $expected): void
    {
        $dto = new EntryListDto(
            new EntryDto(
                new DateDto('2023-01-01'),
                type: $type,
            )
        );

        static::assertSame($expected, $this->calc->getHours($dto));
    }

    public function test_work(): void
    {
        $dto = new EntryListDto(
            new EntryDto(
                new DateDto('2023-01-01'),
                EntryType::Work,
                new WorkTimeDto(
                    '2023-01-01 08:00:00',
                    '2023-01-01 16:00:00',
                )
            )
        );

        static::assertSame((float) 8, $this->calc->getHours($dto));
    }

    public function test_work_multi(): void
    {
        $dto = new EntryListDto(
            new EntryDto(
                new DateDto('2023-01-01'),
                type: EntryType::Vacation,
            ),
            new EntryDto(
                new DateDto('2023-01-01'),
                EntryType::Work,
                new WorkTimeDto(
                    '2023-01-01 08:00:00',
                    '2023-01-01 09:00:00',
                )
            ),
            new EntryDto(
                new DateDto('2023-01-01'),
                EntryType::Work,
                new WorkTimeDto(
                    '2023-01-01 10:00:00',
                    '2023-01-01 11:00:00',
                )
            ),
        );

        static::assertSame((float) 10, $this->calc->getHours($dto));
    }
}
