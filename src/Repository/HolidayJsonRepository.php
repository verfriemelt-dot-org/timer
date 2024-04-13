<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Dto\HolidayListDto;
use timer\Domain\Repository\HolidayRepository;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;
use Override;

final class HolidayJsonRepository implements HolidayRepository
{
    private HolidayListDto $list;

    /** @var array{isHoliday?: HolidayDto[] } */
    private array $cache = [];

    public function __construct(
        private readonly string $path,
    ) {}

    #[Override]
    public function all(): HolidayListDto
    {
        return $this->list ??= (new JsonEncoder())->deserialize($this->read(), HolidayListDto::class);
    }

    #[Override]
    public function add(HolidayDto $holiday): void
    {
        $this->list = new HolidayListDto(
            ...$this->all()->holidays,
            ...[$holiday],
        );

        $this->write($this->list);
    }

    #[Override]
    public function getHoliday(DateTimeImmutable $day): ?HolidayDto
    {
        $dayString = $day->format('Y-m-d');

        if (\array_key_exists($dayString, $this->cache[__METHOD__] ?? [])) {
            return $this->cache[__METHOD__][$dayString];
        }

        foreach ($this->all()->holidays as $holiday) {
            if ($holiday->date->day === $dayString) {
                return $this->cache[__METHOD__][$dayString] = $holiday;
            }
        }

        return $this->cache[__METHOD__][$dayString] = null;
    }

    #[Override]
    public function getByYear(string $year): HolidayListDto
    {
        return new HolidayListDto(
            ...\array_filter(
                $this->all()->holidays,
                static fn (HolidayDto $dto): bool => \str_starts_with($dto->date->day, $year),
            ),
        );
    }

    private function read(): string
    {
        if (!\file_exists($this->path)) {
            return '[]';
        }

        if (!\is_file($this->path)) {
            throw new RuntimeException("cant read {$this->path}, its a directory");
        }

        /** @phpstan-ignore-next-line ignore short ternary */
        return @\file_get_contents($this->path) ?: throw new RuntimeException("cant read {$this->path}");
    }

    private function write(HolidayListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->holidays, true));
        $this->cache = [];
    }
}
