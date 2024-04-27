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

    private ExpectedHoursDto $active;

    public function __construct(
        private readonly string $path,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function getActive(DateTimeImmutable $at): ExpectedHoursDto
    {
        $at = $at->setTime(0, 0, 0, 0);

        if (isset($this->active)) {
            return $this->active;
        }

        foreach ($this->all()->hours as $hours) {
            if (
                $at >= $this->clock->fromString($hours->from->day)->setTime(0, 0, 0, 0)
                && $at < $this->clock->fromString($hours->till->day)->setTime(0, 0, 0, 0)
            ) {
                return $this->active = $hours;
            }
        }

        throw new RuntimeException('no hours defined');
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
        throw new RuntimeException('not implemented');
    }
}
