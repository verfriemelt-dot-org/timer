<?php

declare(strict_types=1);

namespace Unit\Repository;

use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Repository\EntryRepository;
use Override;

class EntryRepositoryTest extends TestCase
{
    private const string TEST_PATH = \TEST_ROOT . '/_data/entrytest.json';

    private EntryRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new EntryRepository(self::TEST_PATH);
        @unlink(self::TEST_PATH);
    }

    #[Override]
    public function tearDown(): void
    {
        @unlink(self::TEST_PATH);
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
        static::assertSame(
            <<<JSON
                [
                    {
                        "date": {
                            "day": "2022-02-02"
                        },
                        "workTime": null,
                        "type": "sick"
                    }
                ]
                JSON,
            file_get_contents(self::TEST_PATH)
        );
    }
}
