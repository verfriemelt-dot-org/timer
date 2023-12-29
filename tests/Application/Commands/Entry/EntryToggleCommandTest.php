<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use DateTimeImmutable;
use timer\Commands\Entry\EntryToggleCommand;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryToggleCommandTest extends ApplicationTestCase
{
    public function test_start(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));

        $repo = $this->kernel->getContainer()->get(CurrentWorkRepositoryInterface::class);
        static::assertInstanceOf(CurrentWorkRepositoryInterface::class, $repo);
        static::assertNull($repo->get()->till);
    }

    public function test_start_with_date(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(EntryToggleCommand::class, ['2000-01-01', '08:00:00'])
        );

        $repo = $this->kernel->getContainer()->get(CurrentWorkRepositoryInterface::class);
        static::assertInstanceOf(CurrentWorkRepositoryInterface::class, $repo);
        static::assertSame('2000-01-01 08:00:00', $repo->get()->from);
    }

    public function test_close(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));

        $currentWorkRepo = $this->kernel->getContainer()->get(CurrentWorkRepositoryInterface::class);
        static::assertInstanceOf(CurrentWorkRepositoryInterface::class, $currentWorkRepo);
        static::assertFalse($currentWorkRepo->has());

        $entryRepo = $this->kernel->getContainer()->get(EntryRepositoryInterface::class);
        static::assertInstanceOf(EntryRepositoryInterface::class, $entryRepo);

        $entryListDto = $entryRepo->getDay(new DateTimeImmutable());
        static::assertCount(1, $entryListDto->entries);
    }
}
