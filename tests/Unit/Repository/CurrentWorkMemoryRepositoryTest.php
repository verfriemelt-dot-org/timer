<?php

declare(strict_types=1);

namespace timer\tests\Unit\Repository;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use timer\Domain\Repository\CurrentWorkRepository;
use timer\Repository\CurrentWorkMemoryRepository;

class CurrentWorkMemoryRepositoryTest extends TestCase
{
    private CurrentWorkRepository $repo;

    #[Override]
    public function setUp(): void
    {
        $this->repo = new CurrentWorkMemoryRepository();
    }

    public function test_empty(): void
    {
        static::assertFalse($this->repo->has());
    }

    public function test_empty_getter(): void
    {
        static::expectException(RuntimeException::class);
        $this->repo->get();
    }

    public function test_toggle_start(): void
    {
        $dto = $this->repo->toggle(new DateTimeImmutable());

        static::assertNull($dto->till);
    }

    public function test_toggle_finish(): void
    {
        $this->repo->toggle(new DateTimeImmutable());
        $dto = $this->repo->toggle(new DateTimeImmutable());

        static::assertNotNull($dto->till);
    }
}
