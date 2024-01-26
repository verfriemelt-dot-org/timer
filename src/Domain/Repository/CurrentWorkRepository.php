<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\WorkTimeDto;

interface CurrentWorkRepository
{
    public function toggle(DateTimeImmutable $time): WorkTimeDto;

    public function has(): bool;

    public function get(): WorkTimeDto;

    public function reset(): void;
}
