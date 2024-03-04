<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use Override;
use timer\Domain\Dto\VacationRuleDto;
use timer\Domain\Dto\VacationRuleListDto;
use timer\Domain\Repository\VacationRuleRepository;

final class VacationRuleMemoryRepository implements VacationRuleRepository
{
    private VacationRuleListDto $list;

    public function __construct(
    ) {
        $this->list = new VacationRuleListDto();
    }

    #[Override]
    public function all(): VacationRuleListDto
    {
        return $this->list;
    }

    #[Override]
    public function add(VacationRuleDto $rule): void
    {
        $this->list = new VacationRuleListDto(
            ...$this->all()->rules,
            ...[$rule],
        );
    }

    #[Override]
    public function getByDate(DateTimeImmutable $date): VacationRuleListDto
    {
        // drop time component
        $date = $date->setTime(0, 0);

        return new VacationRuleListDto(
            ...\array_filter(
                $this->all()->rules,
                static fn (VacationRuleDto $ruleDto): bool => $date >= new DateTimeImmutable($ruleDto->validFrom->day) && $date < new DateTimeImmutable($ruleDto->validTill->day)
            )
        );
    }
}
