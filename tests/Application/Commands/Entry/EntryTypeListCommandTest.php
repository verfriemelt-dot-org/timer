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
              work                     0
              sick                     100
              sick-half                50
              vacation                 100
              vacation-half            50
              special-vacation         100
              mourning-leave           100
              educational-vacation     100
              overtime-reduction       0
            
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }
}
