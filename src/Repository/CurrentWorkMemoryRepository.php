<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use RuntimeException;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\Repository\CurrentWorkRepository;
use Override;

final class CurrentWorkMemoryRepository implements CurrentWorkRepository
{
    private ?WorkTimeDto $current = null;

    #[Override]
    public function toggle(DateTimeImmutable $time): WorkTimeDto
    {
        if (!$this->has()) {
            return $this->current = new WorkTimeDto($time->format('Y-m-d H:i:s'));
        }

        $dto = $this->get();
        $this->reset();

        return $dto->till($time->format('Y-m-d H:i:s'));
    }

    #[Override]
    public function get(): WorkTimeDto
    {
        if ($this->current === null) {
            throw new RuntimeException('session not open');
        }

        return $this->current;
    }

    #[Override]
    public function has(): bool
    {
        return $this->current !== null;
    }

    #[Override]
    public function reset(): void
    {
        $this->current = null;
    }
}
