<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use DateTimeImmutable;
use timer\Commands\Entry\EntryResetCommand;
use timer\Commands\Entry\EntryToggleCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryResetCommandTest extends ApplicationTestCase
{
    public function test_empty(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryResetCommand::class));

        static::assertSame(
            <<<OUTPUT
            not started
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }

    public function test_reset(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryResetCommand::class));
        static::assertFalse($this->currentWorkRepository->has());

        $entryListDto = $this->entryRepository->getDay(new DateTimeImmutable());
        static::assertCount(0, $entryListDto->entries);

        static::assertSame(
            <<<OUTPUT
            deleted
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }
}
