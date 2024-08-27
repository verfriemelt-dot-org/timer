<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use timer\Commands\Entry\EntryTypeListCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryTypeListCommandTest extends ApplicationTestCase
{
    public function test(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryTypeListCommand::class));
        static::assertSame(
            <<<OUTPUT

              type                     factor
              ===============================
              Work                     0
              Sick                     100
              SickHalf                 50
              Vacation                 100
              VacationHalf             50
              SpecialVacation          100
              MourningLeave            100
              EducationalVacation      100
              OvertimeReduction        0
            
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }
}
