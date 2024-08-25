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

use function end;

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
        $at = $at->setTime(0, 0, 0, 0);

        $current = null;
        $lastStart = null;

        foreach ($this->all()->hours as $hours) {
            $start = $this->clock->fromString($hours->from->day)->setTime(0, 0, 0, 0);

            if ($lastStart !== null) {
                \assert($start > $lastStart, 'list must be sorted');
            }

            if ($at >= $start) {
                $current = $hours;
                $lastStart = $start;
            }
        }

        return $current ?? throw new RuntimeException('no hours defined');
    }

    #[Override]
    public function all(): ExpectedHoursListDto
    {
        return $this->list;
    }

    #[Override]
    public function add(ExpectedHoursDto $expectedHoursDto): void
    {
        // readonly + end() does not mix
        $list = $this->all()->hours;
        $last = \end($list);

        if ($last !== false && $this->clock->fromString($last->from->day) > $this->clock->fromString($expectedHoursDto->from->day)) {
            throw new RuntimeException('cannot add hours before the last entry to list');
        }

        $this->list = new ExpectedHoursListDto(
            ... $this->list->hours,
            ... [$expectedHoursDto],
        );
    }

    #[Override]
    public function initialized(): bool
    {
        return true;
    }
}
