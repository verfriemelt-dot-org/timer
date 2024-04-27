<?php

declare(strict_types=1);

namespace timer\tests\Integration;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\WorkHoursDto;
use timer\Domain\Repository\ExpectedHoursRepository;
use timer\Domain\Repository\HolidayRepository;
use timer\Domain\WorkTimeCalculator;
use timer\Repository\ExpectedHoursMemoryRepository;
use timer\Repository\HolidayMemoryRepository;
use verfriemelt\wrapped\_\Clock\MockClock;
use verfriemelt\wrapped\_\DI\Container;
use Override;

class SwitchingHoursTest extends TestCase
{
    private ExpectedHoursRepository $expectedHoursRepository;
    private Container $container;

    #[Override]
    public function setUp(): void
    {
        $this->container = new Container();
        $this->container->register(ClockInterface::class, new MockClock(new DateTimeImmutable('2024-04-01')));

        $this->expectedHoursRepository = $this->container->get(ExpectedHoursMemoryRepository::class);
        $this->expectedHoursRepository->add(
            new ExpectedHoursDto(
                new DateDto('2024-04-01'),
                new DateDto('2024-04-02'),
                new WorkHoursDto(
                    8.0,
                    8.0,
                    8.0,
                    8.0,
                    8.0,
                    8.0,
                    8.0,
                ),
            ),
        );

        $this->expectedHoursRepository->add(
            new ExpectedHoursDto(
                new DateDto('2024-04-02'),
                new DateDto('2024-04-03'),
                new WorkHoursDto(
                    5.0,
                    5.0,
                    5.0,
                    5.0,
                    5.0,
                    5.0,
                    5.0,
                ),
            ),
        );

        $this->container->register(ExpectedHoursRepository::class, $this->expectedHoursRepository);
        $this->container->register(HolidayRepository::class, $this->container->get(HolidayMemoryRepository::class));
    }

    public function test_honoring_source(): void
    {
        $wtc = $this->container->get(WorkTimeCalculator::class);
        static::assertInstanceOf(WorkTimeCalculator::class, $wtc);

        static::assertSame(8.0, $wtc->expectedHours(new DateTimeImmutable('2024-04-01')));
        static::assertSame(5.0, $wtc->expectedHours(new DateTimeImmutable('2024-04-02')));
    }
}
