<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\ExpectedHoursListDto;

interface ExpectedHoursRepositoryInterface
{
    public function all(): ExpectedHoursListDto;

    public function getActive(): ExpectedHoursDto;
}
