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
    case MourningLeave = 'mourning-leave';
    case EducationalVacation = 'educational-vacation';
    case OvertimeReduction = 'overtime-reduction';

    /** @var EntryType[] */
    final public const array VACATION = [
        EntryType::Vacation,
        EntryType::VacationHalf,
        EntryType::SpecialVacation,
        EntryType::MourningLeave,
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
            EntryType::MourningLeave,
            EntryType::EducationalVacation => 100,
        };
    }
}
