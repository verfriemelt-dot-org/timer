<?php

declare(strict_types=1);

namespace timer\Domain\Dto;

final readonly class VacationRuleListDto
{
    /** @var VacationRuleDto[] */
    public array $rules;

    public function __construct(
        VacationRuleDto ...$rules
    ) {
        $this->rules = $rules;
    }
}
