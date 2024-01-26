<?php

declare(strict_types=1);

namespace Unit\Repository;

use PHPUnit\Framework\TestCase;
use timer\Repository\ExpectedHoursMemoryRepository;

class ExpectedHoursMemoryRepositoryTest extends TestCase
{
    public function test_get_active(): void
    {
        $repo =  new ExpectedHoursMemoryRepository();
        $hours = $repo->getActive();

        static::assertSame([1 => 8.0, 2 => 8.0, 3 => 8.0, 4 => 8.0, 5 => 8.0, 6 => 0.0, 7 => 0.0], $hours->hours->toArray());
    }

    public function test_get_all(): void
    {
        $repo =  new ExpectedHoursMemoryRepository();
        $hours = $repo->all()->hours;

        static::assertIsList($hours);
        static::assertCount(1, $hours);
        static::assertSame([1 => 8.0, 2 => 8.0, 3 => 8.0, 4 => 8.0, 5 => 8.0, 6 => 0.0, 7 => 0.0], $hours[0]->hours->toArray());
    }
}
