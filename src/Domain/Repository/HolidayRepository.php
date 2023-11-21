<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTime;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Dto\PublicHolidayListDto;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;

class HolidayRepository extends AbstractRepository
{
    private string $path;

    public function __construct()
    {
        $this->path = \dirname(__FILE__, 4) . '/data/holidays.json';
    }

    public function all(): PublicHolidayListDto
    {
        if (!\file_exists($this->path)) {
            $json = '[]';
        } else {
            $json = \file_get_contents($this->path);
            assert(\is_string($json), "cant read {$this->path}");
        }

        return (new JsonEncoder())->deserialize($json, PublicHolidayListDto::class);
    }

    public function truncate(): void
    {
        \file_put_contents($this->path, '[]');
    }

    public function add(PublicHoliday $publicHoliday): void
    {
        $newList = new PublicHolidayListDto(
            $publicHoliday,
            ...array_values($this->all()->holidays),
        );

        $this->write($newList);
    }

    private function write(PublicHolidayListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->holidays, true));
    }

    public function isHoliday(DateTime $day): bool
    {
        $holidays = array_map(fn (PublicHoliday $holiday): string => $holiday->date->day, $this->all()->holidays);

        return in_array($day->format('Y-m-d'), $holidays, true);
    }
}
