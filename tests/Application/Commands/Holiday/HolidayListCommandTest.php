<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Holiday;

use timer\Commands\Holiday\HolidayListCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

final class HolidayListCommandTest extends ApplicationTestCase
{
    public function test(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(HolidayListCommand::class, []));
    }
}
