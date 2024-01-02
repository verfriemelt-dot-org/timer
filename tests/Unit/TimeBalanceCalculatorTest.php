<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\Domain\TimeBalanceCalculator;
use timer\Domain\TimeDiffCalcalator;
use timer\Domain\WorkTimeCalculator;
use timer\Repository\MemoryEntryRepository;
use timer\Repository\MemoryExpectedHoursRepository;
use timer\Repository\MemoryHolidayRepository;
use verfriemelt\wrapped\_\Clock\MockClock;

class TimeBalanceCalculatorTest extends TestCase
{
    private TimeBalanceCalculator $balanceCalculator;
    private MemoryHolidayRepository $holidayRepository;
    private MemoryEntryRepository $entryRepository;

    #[Override]
    public function setUp(): void
    {
        $this->entryRepository = new MemoryEntryRepository();
        $this->holidayRepository = new MemoryHolidayRepository();

        $this->balanceCalculator = new TimeBalanceCalculator(
            $this->entryRepository,
            new WorkTimeCalculator(
                $this->holidayRepository,
                new TimeDiffCalcalator(new Clock(new MockClock(new DateTimeImmutable('now')))),
                new MemoryExpectedHoursRepository()
            )
        );
    }

    public function test(): void
    {
        $this->holidayRepository->add(
            new PublicHolidayDto(
                new DateDto('2024-01-01'),
                'Neujahr'
            )
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2024-01-02'),
                type: EntryType::Sick,
            )
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2024-01-03'),
                type: EntryType::Vacation,
            )
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2024-01-04'),
                type: EntryType::Work,
                workTime: new WorkTimeDto(
                    '2024-01-04 08:00:00',
                    '2024-01-04 15:00:00',
                )
            )
        );

        $result = $this->balanceCalculator->get(
            new DateTimeImmutable('2024-01-01'),
            new DateTimeImmutable('2024-01-04'),
        );

        static::assertSame((float) 24, $result->expected);
        static::assertSame((float) 8 + 8 + 7, $result->actual);
    }
}
