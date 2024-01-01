<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use timer\Commands\Entry\EntryBalanceCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryBalanceCommandTest extends ApplicationTestCase
{
    public function test_default(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryBalanceCommand::class,
                []
            )
        );

        static::assertSame(
            <<<OUTPUT
            0 // 2080
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }
}
