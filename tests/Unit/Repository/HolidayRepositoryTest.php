<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Repository\HolidayRepository;
use Override;

class HolidayRepositoryTest extends TestCase
{
    private const string TEST_PATH = \TEST_ROOT . '/_data/holidaytest.json';

    private HolidayRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new HolidayRepository(self::getFilepath());
        @unlink(self::getFilepath());
    }

    #[Override]
    public function tearDown(): void
    {
        @unlink(self::getFilepath());
    }

    protected static function getFilepath(): string
    {
        if (($token = \getenv('TEST_TOKEN')) === false) {
            $token = '';
        }

        return self::TEST_PATH . $token;
    }

    public function test_empty(): void
    {
        static::assertCount(0, $this->repo->all()->holidays);
    }

    public function test_add(): void
    {
        $this->repo->add(new PublicHolidayDto(new DateDto('2022-02-02'), 'test'));
        static::assertCount(1, $this->repo->all()->holidays);
        static::assertSame(
            <<<JSON
                [
                    {
                        "date": {
                            "day": "2022-02-02"
                        },
                        "name": "test"
                    }
                ]
                JSON,
            file_get_contents(self::TEST_PATH)
        );
    }

    public function test_is_holiday(): void
    {
        $this->repo->add(new PublicHolidayDto(new DateDto('2022-02-02'), 'test'));
        static::assertTrue($this->repo->isHoliday(new DateTimeImmutable('2022-02-02')));
        static::assertFalse($this->repo->isHoliday(new DateTimeImmutable('2022-02-01')));
    }
}
