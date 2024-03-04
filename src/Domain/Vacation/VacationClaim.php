<?php

declare(strict_types=1);

namespace timer\Domain\Vacation;

use DateTimeImmutable;
use timer\Domain\Dto\VacationRuleDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\VacationRuleRepository;
use RuntimeException;

class VacationClaim
{
    public function __construct(
        private readonly VacationRuleRepository $ruleRepository,
        private readonly EntryRepository $entryRepository,
    ) {}

    public function getTotalVacationDays(DateTimeImmutable $date): float
    {
        $rules = $this->ruleRepository->getByDate($date);
        $claim = 0;

        foreach ($rules->rules as $rule) {
            $claim += $rule->amount;
        }

        return $claim;
    }

    public function getRemainingVacationDays(DateTimeImmutable $date): float
    {
        $remainingClaim = $this->getTotalVacationDays($date);
        $ruleList = $this->ruleRepository->getByDate($date);

        $from = array_reduce(
            $ruleList->rules,
            static fn (DateTimeImmutable $carry, VacationRuleDto $rule): DateTimeImmutable => new DateTimeImmutable($rule->validFrom->day) < $carry ? new DateTimeImmutable($rule->validFrom->day) : $carry,
            new DateTimeImmutable('3000-01-01')
        );
        $till = array_reduce(
            $ruleList->rules,
            static fn (DateTimeImmutable $carry, VacationRuleDto $rule): DateTimeImmutable => new DateTimeImmutable($rule->validTill->day) > $carry ? new DateTimeImmutable($rule->validTill->day) : $carry,
            new DateTimeImmutable('2000-01-01')
        );

        $entries = $this->entryRepository->getByRange($from, $till, EntryType::Vacation, EntryType::VacationHalf);

        foreach ($entries->entries as $entry) {
            $remainingClaim -= match ($entry->type) {
                EntryType::Vacation => 1,
                EntryType::VacationHalf => .5,
                default => throw new RuntimeException('should not happen'),
            };
        }

        return $remainingClaim;
    }
}
