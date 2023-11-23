<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use timer\Domain\Dto\WorkTimeDto;

interface CurrentWorkRepositoryInterface
{
    public function toggle(): WorkTimeDto;

    public function has(): bool;

    public function get(): WorkTimeDto;
}
