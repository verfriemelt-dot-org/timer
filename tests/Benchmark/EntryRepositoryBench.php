<?php

declare(strict_types=1);

use PhpBench\Attributes\Revs;
use timer\Domain\EntryType;
use timer\Repository\EntryRepository;

class EntryRepositoryBench
{
    private readonly EntryRepository $repo;

    public function __construct()
    {
        $this->repo = new EntryRepository(TEST_ROOT . '/_data/entries.benchmark.json');
    }

    #[Revs(1000)]
    public function benchGetDay(): void
    {
        $this->repo->getDay(new DateTimeImmutable('2024-01-03'));
    }

    #[Revs(1000)]
    public function benchGetByType(): void
    {
        $this->repo->getByType(EntryType::Work);
    }
}
