<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Entry;

use DateTimeImmutable;
use timer\Commands\Entry\EntryBalanceCommand;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
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
            0 // 1944 (-1944)
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }

    public function test_over_hours(): void
    {
        $this->clock->set(new DateTimeImmutable('2023-01-03 00:00:00'));
        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-01-02'),
                EntryType::Work,
                new WorkTimeDto(
                    '2023-01-02 08:00:00',
                    '2023-01-02 20:00:00',
                )
            )
        );

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                EntryBalanceCommand::class,
                []
            )
        );

        static::assertSame(
            <<<OUTPUT
            12 // 8 (+4)
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }
}
