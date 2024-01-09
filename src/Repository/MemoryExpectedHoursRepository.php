<?php

declare(strict_types=1);

namespace timer\Repository;

use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\ExpectedHoursListDto;
use timer\Domain\Dto\HoursDto;
use timer\Domain\Repository\ExpectedHoursRepositoryInterface;
use Override;

final readonly class MemoryExpectedHoursRepository implements ExpectedHoursRepositoryInterface
{
    #[Override]
    public function getActive(): ExpectedHoursDto
    {
        return new ExpectedHoursDto(
            new DateDto('2022-01-01'),
            new DateDto('2099-01-01'),
            new HoursDto(8, 8, 8, 8, 8, 0, 0),
        );
    }

    #[Override]
    public function all(): ExpectedHoursListDto
    {
        return new ExpectedHoursListDto($this->getActive());
    }
}
