<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\Repository\CurrentWorkRepository;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;
use Override;

final readonly class CurrentWorkJsonRepository implements CurrentWorkRepository
{
    public function __construct(
        private string $path
    ) {}

    #[Override]
    public function toggle(DateTimeImmutable $time): WorkTimeDto
    {
        if (!$this->has()) {
            $workTime = new WorkTimeDto($time->format('Y-m-d H:i:s'));
            \file_put_contents($this->path, (new JsonEncoder())->serialize($workTime));

            return $workTime;
        }

        $dto = $this->get();
        $this->reset();

        return $dto->till($time->format('Y-m-d H:i:s'));
    }

    #[Override]
    public function get(): WorkTimeDto
    {
        if (!$this->has()) {
            throw new RuntimeException('session not open');
        }

        $json = \file_get_contents($this->path);
        \assert(\is_string($json));

        return (new JsonEncoder())->deserialize($json, WorkTimeDto::class);
    }

    #[Override]
    public function has(): bool
    {
        return \file_exists($this->path);
    }

    #[Override]
    public function reset(): void
    {
        \unlink($this->path);
    }
}
