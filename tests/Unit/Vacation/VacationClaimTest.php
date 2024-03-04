<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\VacationRuleDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\VacationRuleRepository;
use timer\Domain\Vacation\VacationClaim;
use timer\Domain\Vacation\VacationRuleType;
use timer\Repository\EntryMemoryRepository;
use timer\Repository\VacationRuleMemoryRepository;
use Override;

class VacationClaimTest extends TestCase
{
    private VacationRuleRepository $ruleRepository;
    private EntryRepository $entryRepository;
    private VacationClaim $vacationClaim;

    #[Override]
    public function setUp(): void
    {
        $this->ruleRepository = new VacationRuleMemoryRepository();
        $this->entryRepository = new EntryMemoryRepository();
        $this->vacationClaim = new VacationClaim(
            $this->ruleRepository,
            $this->entryRepository,
        );
    }

    public function test_checking_for_rule_validity(): void
    {
        $this->ruleRepository->add(new VacationRuleDto(
            new DateDto('2022-01-01'),
            new DateDto('2023-01-01'),
            VacationRuleType::SingleGrant,
            5
        ));

        $this->ruleRepository->add(new VacationRuleDto(
            new DateDto('2032-01-01'),
            new DateDto('2033-01-01'),
            VacationRuleType::SingleGrant,
            5
        ));

        static::assertSame(5.0, $this->vacationClaim->getTotalVacationDays(new DateTimeImmutable('2022-04-01')));
        static::assertSame(0.0, $this->vacationClaim->getTotalVacationDays(new DateTimeImmutable('2042-04-01')));
    }

    public function test_checking_additive_rules(): void
    {
        $this->ruleRepository->add(new VacationRuleDto(
            new DateDto('2022-01-01'),
            new DateDto('2023-01-01'),
            VacationRuleType::SingleGrant,
            5
        ));

        $this->ruleRepository->add(new VacationRuleDto(
            new DateDto('2022-01-01'),
            new DateDto('2023-01-01'),
            VacationRuleType::SingleGrant,
            2
        ));

        static::assertSame(7.0, $this->vacationClaim->getTotalVacationDays(new DateTimeImmutable('2022-04-01')));
    }

    public function test_claim_partially_depleted(): void
    {
        $this->ruleRepository->add(new VacationRuleDto(
            new DateDto('2022-01-01'),
            new DateDto('2023-04-01'),
            VacationRuleType::SingleGrant,
            5
        ));

        $this->ruleRepository->add(new VacationRuleDto(
            new DateDto('2022-01-01'),
            new DateDto('2023-01-01'),
            VacationRuleType::SingleGrant,
            30
        ));

        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-01'), EntryType::Vacation));
        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-02'), EntryType::Vacation));
        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-03'), EntryType::Vacation));
        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-04'), EntryType::Vacation));
        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-05'), EntryType::Vacation));
        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-07'), EntryType::Vacation));
        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-08'), EntryType::Vacation));
        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-09'), EntryType::Vacation));

        $this->entryRepository->add(new EntryDto(new DateDto('2022-04-10'), EntryType::VacationHalf));

        static::assertSame(26.5, $this->vacationClaim->getRemainingVacationDays(new DateTimeImmutable('2022-04-01')));
    }
}
