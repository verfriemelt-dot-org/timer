<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class HolidayListDto
{
    /** @var HolidayDto[] */
    public array $holidays;

    public function __construct(
        HolidayDto ...$holidays
    ) {
        $this->holidays = $holidays;
    }
}
