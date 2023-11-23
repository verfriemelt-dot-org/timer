<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTime;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Dto\PublicHolidayListDto;

interface HolidayRepositoryInterface
{
    public function all(): PublicHolidayListDto;

    public function add(PublicHoliday $publicHoliday): void;

    public function isHoliday(DateTime $day): bool;
}
