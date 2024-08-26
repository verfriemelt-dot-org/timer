<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Clock;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\ExpectedHoursListDto;
use timer\Domain\Repository\ExpectedHoursRepository;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;
use Override;

final class ExpectedHoursJsonRepository implements ExpectedHoursRepository
{
    private ExpectedHoursListDto $list;

    public function __construct(
        private readonly string $path,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function getActive(DateTimeImmutable $at): ExpectedHoursDto
    {
        $at = $at->setTime(0, 0, 0, 0);

        $current = null;
        $lastStart = null;

        foreach ($this->all()->hours as $hours) {
            $start = $this->clock->fromString($hours->from->day)->setTime(0, 0, 0, 0);

            if ($lastStart !== null) {
                assert($start > $lastStart, 'list must be sorted');
            }

            if ($at >= $start) {
                $current = $hours;
                $lastStart = $start;
            }
        }

        return $current ?? throw new RuntimeException('no hours defined');
    }

    /**
     * @infection-ignore-all
     */
    #[Override]
    public function all(): ExpectedHoursListDto
    {
        return $this->list ??= (new JsonEncoder())->deserialize($this->read(), ExpectedHoursListDto::class);
    }

    private function read(): string
    {
        if (!\file_exists($this->path) || !\is_file($this->path)) {
            throw new RuntimeException("cant read {$this->path}");
        }

        /** @phpstan-ignore-next-line ignore short ternary */
        return \file_get_contents($this->path) ?: throw new RuntimeException("cant read {$this->path}");
    }

    #[Override]
    public function add(ExpectedHoursDto $expectedHoursDto): void
    {
        if ($this->initialized()) {
            // readonly + end() does not mix
            $list = $this->all()->hours;
            $last = end($list);
        } else {
            $last = false;
            $this->list = new ExpectedHoursListDto();
        }

        if ($last !== false && $this->clock->fromString($last->from->day) > $this->clock->fromString($expectedHoursDto->from->day)) {
            throw new RuntimeException('cannot add hours before the last entry to list');
        }

        $this->list = new ExpectedHoursListDto(
            ... $this->list->hours,
            ... [$expectedHoursDto],
        );

        $this->write($this->list);
    }

    #[Override]
    public function initialized(): bool
    {
        try {
            \file_exists($this->path) || throw new RuntimeException();
            $this->getActive($this->clock->now());
        } catch (RuntimeException) {
            return false;
        }

        return true;
    }

    private function write(ExpectedHoursListDto $dto): void
    {
        \file_put_contents($this->path, (new JsonEncoder())->serialize($dto->hours, true));
    }
}
