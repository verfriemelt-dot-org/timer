<?php

declare(strict_types=1);

use PhpBench\Attributes\Revs;
use timer\Domain\Clock;
use timer\Domain\EntryType;
use timer\Repository\EntryJsonRepository;
use verfriemelt\wrapped\_\Clock\SystemClock;

class EntryRepositoryBench
{
    private readonly EntryJsonRepository $repo;

    public function __construct()
    {
        $this->repo = new EntryJsonRepository(TEST_ROOT . '/_data/entries.benchmark.json', new Clock(new SystemClock()));
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
