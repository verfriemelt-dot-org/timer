<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use timer\Commands\Entry\EntryPrintDayCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryPrintDayCommandTest extends ApplicationTestCase
{
    public function test_default(): void
    {
        $this->currentWorkRepository->toggle($this->clock->now()->modify('-1hour'));

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryPrintDayCommand::class,
                []
            )
        );

        static::assertSame(
            <<<OUTPUT
            [1.00 :: 8]
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }

    public function test_raw(): void
    {
        $this->currentWorkRepository->toggle($this->clock->now()->modify('-1hour'));

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryPrintDayCommand::class,
                ['--raw']
            )
        );

        static::assertSame(
            <<<OUTPUT
            timer\Domain\Dto\WorkTimeDto Object
            (
                [from] => {$this->clock->now()->modify('-1hour')->format('Y-m-d H:i:s')}
                [till] => 
            )
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }

    public function test_raw_not_started(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryPrintDayCommand::class,
                ['--raw']
            )
        );

        static::assertSame(
            <<<OUTPUT
            not started
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }
}
