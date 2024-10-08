<?php

declare(strict_types=1);

namespace timer\Repository;

use DateTimeImmutable;
use Override;
use timer\Domain\Clock;
use timer\Domain\Dto\VacationRuleDto;
use timer\Domain\Dto\VacationRuleListDto;
use timer\Domain\Repository\VacationRuleRepository;
use verfriemelt\wrapped\_\Clock\SystemClock;

final class VacationRuleMemoryRepository implements VacationRuleRepository
{
    private VacationRuleListDto $list;

    public function __construct(
        private readonly Clock $clock = new Clock(new SystemClock()),
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
        return new VacationRuleListDto(
            ...\array_filter(
                $this->all()->rules,
                fn (VacationRuleDto $ruleDto): bool => $date >= $this->clock->fromString($ruleDto->validFrom->day) && $date < $this->clock->fromString($ruleDto->validTill->day),
            ),
        );
    }
}
