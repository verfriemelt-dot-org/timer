<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class WorkHoursDto
{
    public function __construct(
        public float $monday,
        public float $tuesday,
        public float $wednesday,
        public float $thursday,
        public float $friday,
        public float $saturday,
        public float $sunday,
    ) {}

    /**
     * @return array{
     *     1: float,
     *     2: float,
     *     3: float,
     *     4: float,
     *     5: float,
     *     6: float,
     *     7: float,
     * }
     */
    public function toArray(): array
    {
        return [
            1 => $this->monday,
            2 => $this->tuesday,
            3 => $this->wednesday,
            4 => $this->thursday,
            5 => $this->friday,
            6 => $this->saturday,
            7 => $this->sunday,
        ];
    }
}
