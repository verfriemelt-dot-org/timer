<?php

declare(strict_types=1);

namespace timer\Domain;

use DateTimeImmutable;
use timer\Domain\Dto\WorkTimeDto;

final class TimeDiff
{
    public function getInSeconds(WorkTimeDto $workTimeDto): float
    {
        \assert(isset($workTimeDto->from, $workTimeDto->till));

        $from = new DateTimeImmutable($workTimeDto->from);
        $to = new DateTimeImmutable($workTimeDto->till);

        $diff = $from->diff($to);

        return $diff->h * 3600 + $diff->i * 60 + $diff->s;
    }

    public function getInHours(WorkTimeDto $workTimeDto): float
    {
        return $this->getInSeconds($workTimeDto) / 3600;
    }
}
