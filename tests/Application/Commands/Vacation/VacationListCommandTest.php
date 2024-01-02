<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Vacation;

use timer\Commands\Vacation\VacationListCommand;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;
use verfriemelt\wrapped\_\DateTime\DateTimeImmutable;

final class VacationListCommandTest extends ApplicationTestCase
{
    public function test(): void
    {
        $date = new DateTimeImmutable('2023-03-01');

        foreach (EntryType::VACATION as $vacationType) {
            $this->entryRepository->add(
                new EntryDto(
                    new DateDto($date->format('Y-m-d')),
                    type: $vacationType
                )
            );
            $date = $date->modify('+1 day');
        }

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(VacationListCommand::class, [])
        );

        static::assertSame(
            <<<OUTPUT
            2023-03-01 vacation
            2023-03-02 vacation-half
            2023-03-03 special-vacation
            2023-03-04 mourning-vacation
            2023-03-05 educational-vacation
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }

    public function test_year_filter(): void
    {
        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2024-01-01'),
                type: EntryType::Vacation
            )
        );

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(VacationListCommand::class, ['2024'])
        );

        static::assertSame(
            <<<OUTPUT
            2024-01-01 vacation
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }
}
