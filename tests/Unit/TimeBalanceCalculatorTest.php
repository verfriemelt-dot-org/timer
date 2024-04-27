<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Dto\WorkHoursDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\Domain\TimeBalanceCalculator;
use timer\Repository\EntryMemoryRepository;
use timer\Repository\ExpectedHoursMemoryRepository;
use timer\Repository\HolidayMemoryRepository;
use verfriemelt\wrapped\_\Clock\MockClock;
use verfriemelt\wrapped\_\DI\Container;

class TimeBalanceCalculatorTest extends TestCase
{
    private TimeBalanceCalculator $balanceCalculator;
    private HolidayMemoryRepository $holidayRepository;
    private EntryMemoryRepository $entryRepository;

    #[Override]
    public function setUp(): void
    {
        $container = new Container();
        $container->register(ClockInterface::class, new MockClock(new DateTimeImmutable('now')));

        $hours = $container->get(ExpectedHoursMemoryRepository::class);
        $this->entryRepository = $container->get(EntryMemoryRepository::class);
        $this->holidayRepository = $container->get(HolidayMemoryRepository::class);
        $this->balanceCalculator = $container->get(TimeBalanceCalculator::class);

        $hours->add(
            new ExpectedHoursDto(
                new DateDto('1999-01-01'),
                new DateDto('2999-01-01'),
                new WorkHoursDto(
                    8.0,
                    8.0,
                    8.0,
                    8.0,
                    8.0,
                    0,
                    0,
                ),
            ),
        );
    }

    public function test(): void
    {
        $this->holidayRepository->add(
            new HolidayDto(
                new DateDto('2024-01-01'),
                'Neujahr',
            ),
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2024-01-02'),
                type: EntryType::Sick,
            ),
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2024-01-03'),
                type: EntryType::Vacation,
            ),
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2024-01-04'),
                type: EntryType::Work,
                workTime: new WorkTimeDto(
                    '2024-01-04 08:00:00',
                    '2024-01-04 15:00:00',
                ),
            ),
        );

        $result = $this->balanceCalculator->get(
            new DateTimeImmutable('2024-01-01'),
            new DateTimeImmutable('2024-01-04'),
        );

        static::assertSame((float) 24, $result->expected);
        static::assertSame((float) 8 + 8 + 7, $result->actual);
    }
}
