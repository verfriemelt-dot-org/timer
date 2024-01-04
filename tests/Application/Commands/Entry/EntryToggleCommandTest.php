<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use timer\Commands\Entry\EntryToggleCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryToggleCommandTest extends ApplicationTestCase
{
    public function test_start(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));
        static::assertNull($this->currentWorkRepository->get()->till);
        static::assertSame(
            $this->clock->now()->format('Y-m-d H:i:s'),
            $this->currentWorkRepository->get()->from,
            'current time as start'
        );
    }

    public function test_start_with_date(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(EntryToggleCommand::class, ['2000-01-01', '08:00:00'])
        );

        static::assertSame('2000-01-01 08:00:00', $this->currentWorkRepository->get()->from);
    }

    public function test_close(): void
    {
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));
        static::assertSame(ExitCode::Success, $this->executeCommand(EntryToggleCommand::class));

        static::assertFalse($this->currentWorkRepository->has());

        $entryListDto = $this->entryRepository->getDay($this->clock->now());
        static::assertCount(1, $entryListDto->entries);
    }

    public function test_with_argument(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryToggleCommand::class,
                ['--', '-30min']
            )
        );

        static::assertSame(
            $this->clock->now()->modify('-30mins')->format('Y-m-d H:i:s'),
            $this->currentWorkRepository->get()->from,
            'should be relative to current time'
        );
    }
}
