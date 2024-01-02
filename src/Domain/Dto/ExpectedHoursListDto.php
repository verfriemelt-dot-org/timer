<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

class ExpectedHoursListDto
{
    /** @var ExpectedHoursDto[] */
    public array $hours;

    public function __construct(
        ExpectedHoursDto ...$hours
    ) {
        $this->hours = $hours;
    }
}
