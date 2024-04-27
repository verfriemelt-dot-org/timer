<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\ExpectedHoursListDto;

interface ExpectedHoursRepository
{
    public function all(): ExpectedHoursListDto;

    public function getActive(DateTimeImmutable $at): ExpectedHoursDto;

    public function add(ExpectedHoursDto $expectedHoursDto): void;
}
