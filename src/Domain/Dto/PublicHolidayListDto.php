<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class PublicHolidayListDto
{
    /** @var PublicHolidayDto[] */
    public array $holidays;

    public function __construct(
        PublicHolidayDto ...$holidays
    ) {
        $this->holidays = $holidays;
    }
}
