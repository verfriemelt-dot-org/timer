<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use timer\Repository\EntryMemoryRepository;
use verfriemelt\wrapped\_\Clock\SystemClock;

class EntryMemoryRepositoryTest extends TestCase
{
    private EntryRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new EntryMemoryRepository(new Clock(new SystemClock()));
    }

    public function test_empty(): void
    {
        static::assertCount(0, $this->repo->all()->entries);
    }

    public function test_add(): void
    {
        $this->repo->add(
            new EntryDto(
                new DateDto('2022-02-02'),
                type: EntryType::Sick
            )
        );

        static::assertCount(1, $this->repo->all()->entries);
    }

    public function test_get_day(): void
    {
        $this->repo->add(new EntryDto(new DateDto('2022-02-02'), type: EntryType::Sick));
        $this->repo->add(new EntryDto(new DateDto('2022-02-03'), type: EntryType::Sick));
        $this->repo->add(new EntryDto(new DateDto('2022-02-03'), type: EntryType::Sick));

        static::assertCount(1, $this->repo->getDay(new DateTimeImmutable('2022-02-02'))->entries);
        static::assertCount(2, $this->repo->getDay(new DateTimeImmutable('2022-02-03'))->entries);
        static::assertCount(0, $this->repo->getDay(new DateTimeImmutable('2022-02-04'))->entries);
    }

    public function test_get_by_type(): void
    {
        $this->repo->add(new EntryDto(new DateDto('2022-02-01'), type: EntryType::Work));
        $this->repo->add(new EntryDto(new DateDto('2022-02-01'), type: EntryType::Work));
        $this->repo->add(new EntryDto(new DateDto('2022-02-01'), type: EntryType::Work));
        $this->repo->add(new EntryDto(new DateDto('2022-02-03'), type: EntryType::Vacation));
        $this->repo->add(new EntryDto(new DateDto('2022-02-03'), type: EntryType::Vacation));
        $this->repo->add(new EntryDto(new DateDto('2022-02-03'), type: EntryType::Sick));

        static::assertCount(3, $this->repo->getByType(EntryType::Work)->entries);
        static::assertCount(2, $this->repo->getByType(EntryType::Vacation)->entries);
        static::assertCount(1, $this->repo->getByType(EntryType::Sick)->entries);
    }
}
