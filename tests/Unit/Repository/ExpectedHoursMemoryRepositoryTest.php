<?php

declare(strict_types=1);

namespace Unit\Repository;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use RuntimeException;
use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\WorkHoursDto;
use timer\Repository\ExpectedHoursMemoryRepository;
use verfriemelt\wrapped\_\Clock\MockClock;
use verfriemelt\wrapped\_\Clock\SystemClock;
use verfriemelt\wrapped\_\DI\Container;
use Override;

class ExpectedHoursMemoryRepositoryTest extends TestCase
{
    private ExpectedHoursMemoryRepository $expectedHoursMemoryRepository;

    #[Override]
    public function setUp(): void
    {
        $container = (new Container());
        $container->register(ClockInterface::class, new MockClock(new DateTimeImmutable('2024-01-01')));
        $this->expectedHoursMemoryRepository =  $container->get(ExpectedHoursMemoryRepository::class);

        $this->expectedHoursMemoryRepository->add(
            new ExpectedHoursDto(
                new DateDto('1999-01-01'),
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
        $this->expectedHoursMemoryRepository->add(
            new ExpectedHoursDto(
                new DateDto('2999-01-01'),
                new WorkHoursDto(
                    5.0,
                    5.0,
                    5.0,
                    5.0,
                    5.0,
                    0,
                    0,
                ),
            ),
        );
    }

    public function test_get_active(): void
    {
        static::assertSame([1 => 8.0, 2 => 8.0, 3 => 8.0, 4 => 8.0, 5 => 8.0, 6 => 0.0, 7 => 0.0], $this->expectedHoursMemoryRepository->getActive(new DateTimeImmutable('2024-01-01'))->hours->toArray());
    }

    public function test_get_all(): void
    {
        $hours = $this->expectedHoursMemoryRepository->all()->hours;

        static::assertIsList($hours);
        static::assertCount(2, $hours);
        static::assertSame([1 => 8.0, 2 => 8.0, 3 => 8.0, 4 => 8.0, 5 => 8.0, 6 => 0.0, 7 => 0.0], $hours[0]->hours->toArray());
        static::assertSame([1 => 5.0, 2 => 5.0, 3 => 5.0, 4 => 5.0, 5 => 5.0, 6 => 0.0, 7 => 0.0], $hours[1]->hours->toArray());
    }

    public function test_add_older_entry(): void
    {
        static::expectException(RuntimeException::class);
        static::expectExceptionMessage('before');

        $repo =  new ExpectedHoursMemoryRepository(
            new Clock(new SystemClock()),
        );

        $repo->add(
            new ExpectedHoursDto(
                new DateDto('2000-01-01'),
                new WorkHoursDto(.5, .5, .5, .5, .5, .5, .5),
            ),
        );

        $repo->add(
            new ExpectedHoursDto(
                new DateDto('1900-01-01'),
                new WorkHoursDto(.5, .5, .5, .5, .5, .5, .5),
            ),
        );
    }
}
