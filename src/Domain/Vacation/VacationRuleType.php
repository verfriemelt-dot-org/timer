<?php

declare(strict_types=1);

namespace timer\Domain\Vacation;

enum VacationRuleType: string
{
    case SingleGrant = 'single-grant';
    case RecurringGrant = 'recurring-grant';
}
