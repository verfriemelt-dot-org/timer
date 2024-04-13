<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\VacationRuleDto;
use timer\Domain\Vacation\VacationRuleType;
use timer\Repository\VacationRuleMemoryRepository;

class VacationRuleMemoryRepositoryTest extends TestCase
{
    private VacationRuleMemoryRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new VacationRuleMemoryRepository();
    }

    public function test_empty(): void
    {
        static::assertCount(0, $this->repo->all()->rules);
    }

    public function test_add(): void
    {
        $rule = new VacationRuleDto(
            new DateDto('2022-02-02'),
            new DateDto('2022-03-03'),
            VacationRuleType::SingleGrant,
            5,
        );

        $this->repo->add($rule);

        static::assertCount(1, $this->repo->all()->rules);
        static::assertSame($rule, $this->repo->all()->rules[0]);
    }

    public function test_get_by_date(): void
    {
        $this->repo->add(
            new VacationRuleDto(
                new DateDto('2022-02-01'),
                new DateDto('2022-02-02'),
                VacationRuleType::SingleGrant,
                5,
            ),
        );

        static::assertCount(1, $this->repo->getByDate(new DateTimeImmutable('2022-02-01'))->rules);
        static::assertCount(0, $this->repo->getByDate(new DateTimeImmutable('2022-01-31'))->rules);
        static::assertCount(0, $this->repo->getByDate(new DateTimeImmutable('2022-02-02'))->rules);
    }
}
