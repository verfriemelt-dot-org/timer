<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use timer\Domain\Dto\WorkTimeDto;

interface CurrentWorkRepositoryInterface
{
    public function toggle(string $timeString): WorkTimeDto;

    public function has(): bool;

    public function get(): WorkTimeDto;

    public function reset(): void;
}
