<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use DateTimeImmutable;
use timer\Commands\Entry\EntryResetCommand;
use timer\Commands\Entry\EntryToggleCommand;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryResetCommandTest extends ApplicationTestCase
{
    public function test_empty(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryResetCommand::class));
    }

    public function test_reset(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryResetCommand::class));

        $currentWorkRepo = $this->kernel->getContainer()->get(CurrentWorkRepositoryInterface::class);
        static::assertInstanceOf(CurrentWorkRepositoryInterface::class, $currentWorkRepo);
        static::assertFalse($currentWorkRepo->has());

        $entryRepo = $this->kernel->getContainer()->get(EntryRepositoryInterface::class);
        static::assertInstanceOf(EntryRepositoryInterface::class, $entryRepo);

        $entryListDto = $entryRepo->getDay(new DateTimeImmutable());
        static::assertCount(0, $entryListDto->entries);
    }
}
