<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Dto\PublicHolidayListDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;

final class HolidayRepository implements HolidayRepositoryInterface
{
    private PublicHolidayListDto $list;

    public function __construct(
        private readonly string $path
    ) {}

    public function all(): PublicHolidayListDto
    {
        return $this->list ??= (new JsonEncoder())->deserialize($this->read(), PublicHolidayListDto::class);
    }

    public function add(PublicHolidayDto $publicHoliday): void
    {
        $this->list = new PublicHolidayListDto(
            ...$this->all()->holidays,
            ...[$publicHoliday],
        );

        $this->write($this->list);
    }

    public function isHoliday(DateTimeImmutable $day): bool
    {
        $holidays = \array_map(fn (PublicHolidayDto $holiday): string => $holiday->date->day, $this->all()->holidays);

        return \in_array($day->format('Y-m-d'), $holidays, true);
    }

    public function getByYear(string $year): PublicHolidayListDto
    {
        return new PublicHolidayListDto(
            ...\array_filter(
                $this->all()->holidays,
                static fn (PublicHolidayDto $dto): bool => \str_starts_with($dto->date->day, $year)
            )
        );
    }

    private function read(): string
    {
        if (!\file_exists($this->path)) {
            return '[]';
        }

        /** @phpstan-ignore-next-line */
        return \file_get_contents($this->path) ?: throw new RuntimeException("cant read {$this->path}");
    }

    private function write(PublicHolidayListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->holidays, true));
    }
}
