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
    case OvertimeReduction = 'overtime-reduction';

    /** @var EntryType[] */
    final public const array VACATION = [
        EntryType::Vacation,
        EntryType::VacationHalf,
        EntryType::SpecialVacation,
        EntryType::MourningVacation,
        EntryType::EducationalVacation,
    ];

    public function getFactor(): int
    {
        return match ($this) {
            EntryType::OvertimeReduction,
            EntryType::Work => 0,
            EntryType::VacationHalf,
            EntryType::SickHalf => 50,
            EntryType::Sick,
            EntryType::Vacation,
            EntryType::SpecialVacation,
            EntryType::MourningVacation,
            EntryType::EducationalVacation => 100,
        };
    }
}
