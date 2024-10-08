<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use timer\Commands\Entry\EntryAddCommand;
use timer\Domain\EntryType;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class EntryAddCommandTest extends ApplicationTestCase
{
    public function test_sick(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryAddCommand::class,
                ['sick'],
            ),
        );

        $dto = $this->entryRepository->getDay($this->clock->now());
        $entry = $dto->entries[0] ?? static::fail('entry not found');

        static::assertSame(EntryType::Sick, $entry->type);
        static::assertSame($this->clock->now()->format('Y-m-d'), $entry->date->day);
        static::assertNull($entry->workTime);
    }

    public function test_vacation(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryAddCommand::class,
                ['vacation'],
            ),
        );

        $dto = $this->entryRepository->getDay($this->clock->now());
        $entry = $dto->entries[0] ?? static::fail('entry not found');

        static::assertSame(EntryType::Vacation, $entry->type);
        static::assertSame($this->clock->now()->format('Y-m-d'), $entry->date->day);
        static::assertNull($entry->workTime);
    }
}
