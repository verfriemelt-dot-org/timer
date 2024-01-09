<?php

declare(strict_types=1);

namespace timer\Repository;

use timer\Domain\Clock;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\ExpectedHoursListDto;
use timer\Domain\Repository\ExpectedHoursRepositoryInterface;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;
use Override;

final class ExpectedHoursRepository implements ExpectedHoursRepositoryInterface
{
    private ExpectedHoursListDto $list;

    private ExpectedHoursDto $active;

    public function __construct(
        private readonly string $path,
        private readonly Clock $clock,
    ) {}

    #[Override]
    public function getActive(): ExpectedHoursDto
    {
        if (isset($this->active)) {
            return $this->active;
        }

        foreach ($this->all()->hours as $hours) {
            if (
                $this->clock->now() >= $this->clock->fromString($hours->from->day)
                && $this->clock->now() < $this->clock->fromString($hours->till->day)
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
        /** @phpstan-ignore-next-line ignore short ternary */
        return @\file_get_contents($this->path) ?: throw new RuntimeException("cant read {$this->path}");
    }
}
