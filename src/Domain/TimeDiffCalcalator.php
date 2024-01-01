<?php

declare(strict_types=1);

namespace timer\Domain;

use timer\Domain\Dto\WorkTimeDto;

final readonly class TimeDiffCalcalator
{
    public function __construct(
        private Clock $clock,
    ) {}

    public function getInSeconds(WorkTimeDto $workTimeDto): float
    {
        \assert(isset($workTimeDto->from, $workTimeDto->till));

        $from = $this->clock->fromString($workTimeDto->from);
        $to = $this->clock->fromString($workTimeDto->till);

        $diff = $from->diff($to);

        return $diff->h * 3600 + $diff->i * 60 + $diff->s;
    }

    public function getInHours(WorkTimeDto $workTimeDto): float
    {
        return $this->getInSeconds($workTimeDto) / 3600;
    }
}
