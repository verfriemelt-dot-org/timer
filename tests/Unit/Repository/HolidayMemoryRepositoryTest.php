<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Repository\HolidayRepository;
use timer\Repository\HolidayMemoryRepository;

class HolidayMemoryRepositoryTest extends TestCase
{
    private HolidayRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new HolidayMemoryRepository();
    }

    public function test_empty(): void
    {
        static::assertCount(0, $this->repo->all()->holidays);
    }

    public function test_add(): void
    {
        $this->repo->add(new HolidayDto(new DateDto('2022-02-02'), 'test'));
        static::assertCount(1, $this->repo->all()->holidays);
    }

    public function test_is_holiday(): void
    {
        $this->repo->add(new HolidayDto(new DateDto('2022-02-02'), 'test'));
        static::assertNotNull($this->repo->getHoliday(new DateTimeImmutable('2022-02-02')));
        static::assertNull($this->repo->getHoliday(new DateTimeImmutable('2022-02-01')));
    }

    public function test_filter_by_year(): void
    {
        $this->repo->add(new HolidayDto(new DateDto('2022-01-01'), 'test 1'));
        $this->repo->add(new HolidayDto(new DateDto('2023-01-01'), 'test 1'));
        static::assertCount(1, $this->repo->getByYear('2023')->holidays);
    }
}
