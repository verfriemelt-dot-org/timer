<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use RuntimeException;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;

final class MemoryCurrentWorkRepository implements CurrentWorkRepositoryInterface
{
    private ?WorkTimeDto $current = null;

    public function toggle(DateTimeImmutable $time): WorkTimeDto
    {
        if (!$this->has()) {
            return $this->current = new WorkTimeDto($time->format('Y-m-d H:i:s'));
        }

        $dto = $this->get();
        $this->reset();

        return $dto->till($time->format('Y-m-d H:i:s'));
    }

    public function get(): WorkTimeDto
    {
        if ($this->current === null) {
            throw new RuntimeException('session not open');
        }

        return $this->current;
    }

    public function has(): bool
    {
        return $this->current !== null;
    }

    public function reset(): void
    {
        $this->current = null;
    }
}
