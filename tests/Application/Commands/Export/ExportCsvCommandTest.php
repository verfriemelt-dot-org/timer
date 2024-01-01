<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Holiday;

use timer\Commands\Export\ExportCsvCommand;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\ExitCode;

final class ExportCsvCommandTest extends ApplicationTestCase
{
    public function test(): void
    {
        $cli = new class () extends Console {
            private string $buffer = '';

            public function write(string $text, ?int $color = null): static
            {
                $this->buffer .= $text;
                return $this;
            }

            public function getBuffer(): string
            {
                return $this->buffer;
            }
        };

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-04-01'),
                new WorkTimeDto(
                    '2023-04-01 08:00:00',
                    '2023-04-01 16:00:00',
                ),
                EntryType::Work
            ),
        );
        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-04-02'),
                type: EntryType::Sick
            )
        );
        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-12-01'),
                type: EntryType::Vacation
            )
        );

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(ExportCsvCommand::class, [], $cli)
        );

        static::assertSame(
            <<<OUT
            work;2023-04-01;2023-04-01 08:00:00;2023-04-01 16:00:00
            sick;2023-04-02;;
            vacation;2023-12-01;;
            
            OUT,
            $cli->getBuffer()
        );
    }
}
