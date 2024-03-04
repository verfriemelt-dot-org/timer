<?php

declare(strict_types=1);

namespace timer\Domain\Repository;

use DateTimeImmutable;
use timer\Domain\Dto\VacationRuleDto;
use timer\Domain\Dto\VacationRuleListDto;

interface VacationRuleRepository
{
    public function all(): VacationRuleListDto;

    public function add(VacationRuleDto $rule): void;

    public function getByDate(DateTimeImmutable $date): VacationRuleListDto;
}
