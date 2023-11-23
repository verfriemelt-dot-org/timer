<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTime;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;
use RuntimeException;

class CurrentWorkRepository implements CurrentWorkRepositoryInterface
{
    private readonly string $path;

    public function __construct()
    {
        $this->path = \dirname(__FILE__, 3) . '/data/current.json';
    }

    public function toggle(): WorkTimeDto
    {
        if (!$this->has()) {
            $workTime = new WorkTimeDto((new DateTime())->format('Y-m-d H:i:s'));
            \file_put_contents($this->path, (new JsonEncoder())->serialize($workTime));

            return $workTime;
        }

        $dto = $this->get();

        \unlink($this->path);

        return $dto->till((new DateTime())->format('Y-m-d H:i:s'));
    }

    public function get(): WorkTimeDto
    {
        if (!$this->has()) {
            throw new RuntimeException('session not open');
        }

        $json = \file_get_contents($this->path);
        \assert(\is_string($json));

        return (new JsonEncoder())->deserialize($json, WorkTimeDto::class);
    }

    public function has(): bool
    {
        return \file_exists($this->path);
    }
}
