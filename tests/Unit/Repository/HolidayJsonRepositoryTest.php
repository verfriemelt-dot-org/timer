<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\HolidayDto;
use timer\Repository\HolidayJsonRepository;

class HolidayJsonRepositoryTest extends TestCase
{
    private const string TEST_PATH = \TEST_ROOT . '/_data/holidaytest.json';

    private HolidayJsonRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new HolidayJsonRepository(self::getFilepath());
        if (\file_exists(self::getFilepath())) {
            \unlink(self::getFilepath());
        }
    }

    #[Override]
    public function tearDown(): void
    {
        if (\file_exists(self::getFilepath())) {
            \unlink(self::getFilepath());
        }
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
        $this->repo->add(new HolidayDto(new DateDto('2022-02-02'), 'test'));
        static::assertCount(1, $this->repo->all()->holidays);
        static::assertSame(
            <<<JSON
                [
                    {
                        "date": {
                            "day": "2022-02-02"
                        },
                        "name": "test",
                        "factor": 100
                    }
                ]
                JSON,
            file_get_contents(self::TEST_PATH)
        );
    }

    public function test_is_holiday(): void
    {
        $this->repo->add(new HolidayDto(new DateDto('2022-02-02'), 'test'));
        static::assertNotNull($this->repo->getHoliday(new DateTimeImmutable('2022-02-02')));
        static::assertNull($this->repo->getHoliday(new DateTimeImmutable('2022-02-01')));

        static::assertNotNull($this->repo->getHoliday(new DateTimeImmutable('2022-02-02')), 'cache hit');
        static::assertNull($this->repo->getHoliday(new DateTimeImmutable('2022-02-01')), 'cache hit');
    }

    public function test_filter_by_year(): void
    {
        $this->repo->add(new HolidayDto(new DateDto('2022-02-02'), 'test 1'));
        $this->repo->add(new HolidayDto(new DateDto('2023-02-02'), 'test 1'));
        static::assertCount(1, $this->repo->getByYear('2023')->holidays);
    }

    public function test_illegal_file_as_storage(): void
    {
        static::expectException(RuntimeException::class);
        (new HolidayJsonRepository(\TEST_ROOT))->all();
    }
}
