<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use Override;
use PHPUnit\Framework\TestCase;
use timer\Repository\CurrentWorkRepository;

class CurrentWorkRepositoryTest extends TestCase
{
    private const string TEST_PATH = \TEST_ROOT . '/_data/worktest.json';

    private CurrentWorkRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new CurrentWorkRepository(self::TEST_PATH);
        @unlink(self::TEST_PATH);
    }

    #[Override]
    public function tearDown(): void
    {
        @unlink(self::TEST_PATH);
    }

    public function test_empty(): void
    {
        static::assertFalse($this->repo->has());
    }

    public function test_toggle_start(): void
    {
        $dto = $this->repo->toggle();
        static::assertNull($dto->till);

        static::assertFileExists(self::TEST_PATH);
    }

    public function test_toggle_finish(): void
    {
        $this->repo->toggle();
        $dto = $this->repo->toggle();
        static::assertNotNull($dto->till);
        static::assertFileDoesNotExist(self::TEST_PATH);
    }
}
