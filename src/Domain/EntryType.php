<?php

declare(strict_types=1);

namespace timer\Domain;

enum EntryType: string
{
    case Work = 'work';
    case Sick = 'sick';
    case SickHalf = 'sick-half';
    case Vacation = 'vacation';
    case VacationHalf = 'vacation-half';
    case SpecialVacation = 'special-vacation';
    case MourningVacation = 'mourning-vacation';
    case EducationalVacation = 'educational-vacation';

    /** @var EntryType[] */
    final public const array VACATION = [
        EntryType::Vacation,
        EntryType::VacationHalf,
        EntryType::SpecialVacation,
        EntryType::EducationalVacation,
    ];

    public function getFactor(): int
    {
        return match ($this) {
            EntryType::Work => 0,
            EntryType::VacationHalf => 50,
            EntryType::SickHalf => 50,
            EntryType::Sick,
            EntryType::Vacation,
            EntryType::SpecialVacation,
            EntryType::MourningVacation,
            EntryType::EducationalVacation => 100,
        };
    }
}
