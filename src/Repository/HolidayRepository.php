<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Dto\PublicHolidayListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;

class HolidayRepository implements HolidayRepositoryInterface
{
    private readonly string $path;

    public function __construct()
    {
        $this->path = \dirname(__FILE__, 3) . '/data/holidays.json';
    }

    public function all(): PublicHolidayListDto
    {
        if (!\file_exists($this->path)) {
            throw new RuntimeException('holidays file not present');
        }

        $json = \file_get_contents($this->path);
        assert(\is_string($json), "cant read {$this->path}");

        return (new JsonEncoder())->deserialize($json, PublicHolidayListDto::class);
    }

    public function add(PublicHoliday $publicHoliday): void
    {
        $newList = new PublicHolidayListDto(
            $publicHoliday,
            ...array_values($this->all()->holidays),
        );

        $this->write($newList);
    }

    public function isHoliday(DateTimeImmutable $day): bool
    {
        $holidays = \array_map(fn (PublicHoliday $holiday): string => $holiday->date->day, $this->all()->holidays);

        return \in_array($day->format('Y-m-d'), $holidays, true);
    }

    private function write(PublicHolidayListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->holidays, true));
    }
}
