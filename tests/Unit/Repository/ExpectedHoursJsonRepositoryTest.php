<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use timer\Domain\Clock;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\WorkHoursDto;
use timer\Repository\ExpectedHoursJsonRepository;
use verfriemelt\wrapped\_\Clock\MockClock;
use verfriemelt\wrapped\_\Clock\SystemClock;

class ExpectedHoursJsonRepositoryTest extends TestCase
{
    private const string TEST_PATH = \TEST_ROOT . '/_data/hours.json';

    #[Override]
    public function setUp(): void
    {
        \file_put_contents(
            self::getFilepath(),
            <<<JSON
            [
               {
                    "from": {
                        "day": "1999-01-01"
                    },
                    "hours": {
                        "monday": 5,
                        "tuesday": 5,
                        "wednesday": 5,
                        "thursday": 5,
                        "friday": 5,
                        "saturday": 0,
                        "sunday": 0
                    }
                },
                {
                    "from": {
                        "day": "2022-01-01"
                    },
                    "hours": {
                        "monday": 8,
                        "tuesday": 8,
                        "wednesday": 8,
                        "thursday": 8,
                        "friday": 8,
                        "saturday": 0,
                        "sunday": 0
                    }
                }
            ]
            JSON,
        );
    }

    protected static function getFilepath(): string
    {
        if (($token = \getenv('TEST_TOKEN')) === false) {
            $token = '';
        }

        return self::TEST_PATH . $token;
    }

    #[Override]
    public function tearDown(): void
    {
        @unlink(self::getFilepath());
    }

    public function test_get_active(): void
    {
        $repo =  new ExpectedHoursJsonRepository(
            self::getFilepath(),
            $clock = new Clock(new MockClock(new DateTimeImmutable('2022-01-01'))),
        );
        $hours = $repo->getActive($clock->today());

        static::assertSame([1 => 8.0, 2 => 8.0, 3 => 8.0, 4 => 8.0, 5 => 8.0, 6 => 0.0, 7 => 0.0], $hours->hours->toArray());
    }

    public function test_get_old_dataset(): void
    {
        $repo =  new ExpectedHoursJsonRepository(
            self::getFilepath(),
            $clock = new Clock(new MockClock(new DateTimeImmutable('1999-01-01'))),
        );
        $hours = $repo->getActive($clock->today());

        static::assertSame([1 => 5.0, 2 => 5.0, 3 => 5.0, 4 => 5.0, 5 => 5.0, 6 => 0.0, 7 => 0.0], $hours->hours->toArray());
    }

    public function test_get_active_undefined(): void
    {
        static::expectException(RuntimeException::class);
        static::expectExceptionMessage('no hours defined');

        $repo =  new ExpectedHoursJsonRepository(
            self::getFilepath(),
            $clock = new Clock(new MockClock(new DateTimeImmutable('1900-01-01'))),
        );
        $repo->getActive($clock->today());
    }

    public function test_illegal_file_as_storage(): void
    {
        static::expectException(RuntimeException::class);
        (new ExpectedHoursJsonRepository(\TEST_ROOT, new Clock(new SystemClock())))->all();
    }

    public function test_add_older_entry(): void
    {
        static::expectException(RuntimeException::class);
        static::expectExceptionMessage('before');

        $repo =  new ExpectedHoursJsonRepository(
            self::getFilepath(),
            new Clock(new SystemClock()),
        );

        $repo->add(
            new ExpectedHoursDto(
                new DateDto('2000-01-01'),
                new WorkHoursDto(.5, .5, .5, .5, .5, .5, .5),
            ),
        );
    }

    public function test_entry(): void
    {
        $repo =  new ExpectedHoursJsonRepository(
            self::getFilepath(),
            new Clock(new SystemClock()),
        );

        $repo->add(
            new ExpectedHoursDto(
                new DateDto('2100-01-01'),
                new WorkHoursDto(.5, .5, .5, .5, .5, .5, .5),
            ),
        );

        static::assertCount(3, $repo->all()->hours);
    }

    public function test_initialized(): void
    {
        $repo =  new ExpectedHoursJsonRepository(
            self::getFilepath(),
            new Clock(new SystemClock()),
        );

        static::assertTrue($repo->initialized());
    }

    public function test_not_initialized(): void
    {
        $repo =  new ExpectedHoursJsonRepository(
            '/foo/path',
            new Clock(new SystemClock()),
        );

        static::assertFalse($repo->initialized());
    }
}
