<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Clock;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\ExpectedHoursListDto;
use timer\Domain\Repository\ExpectedHoursRepository;
use Override;
use RuntimeException;

final class ExpectedHoursMemoryRepository implements ExpectedHoursRepository
{
    private ExpectedHoursListDto $list;

    public function __construct(
        private readonly Clock $clock,
    ) {
        $this->list = new ExpectedHoursListDto();
    }

    #[Override]
    public function getActive(DateTimeImmutable $at): ExpectedHoursDto
    {
        $day = $at->setTime(0, 0, 0, 0);

        foreach ($this->list->hours as $hours) {
            if (
                $day >= $this->clock->fromString($hours->from->day)->setTime(0, 0, 0, 0)
                && $day < $this->clock->fromString($hours->till->day)->setTime(0, 0, 0, 0)
            ) {
                return $hours;
            }
        }

        throw new RuntimeException('no hours defined');
    }

    #[Override]
    public function all(): ExpectedHoursListDto
    {
        return $this->list;
    }

    #[Override]
    public function add(ExpectedHoursDto $expectedHoursDto): void
    {
        $this->list = new ExpectedHoursListDto(
            ... $this->list->hours,
            ... [$expectedHoursDto],
        );
    }
}
