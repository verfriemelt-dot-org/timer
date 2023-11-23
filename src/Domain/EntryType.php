<?php

declare(strict_types=1);

namespace timer\Domain;

enum EntryType: string
{
    case Sick = 'sick';
    case Work = 'work';
    case Vacation = 'vacation';
}
