<?php

declare(strict_types=1);

namespace timer\tests;

use DateTimeImmutable;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\Domain\Print\PrettyPrinter;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Cli\BufferedOutput;

class PrettyPrintTest extends ApplicationTestCase
{
    public function test(): void
    {
        $printer = $this->kernel->getContainer()->get(PrettyPrinter::class);
        static::assertInstanceOf(PrettyPrinter::class, $printer);

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-01-02'),
                type: EntryType::Vacation,
            ),
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-01-02'),
                type: EntryType::Sick,
            ),
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-01-02'),
                EntryType::Work,
                new WorkTimeDto(
                    '2023-01-02 08:00:00',
                    '2023-01-02 12:00:00',
                ),
            ),
        );

        $this->holidayRepository->add(new HolidayDto(new DateDto('2023-01-02'), 'test-holiday'));

        $spy = new BufferedOutput();

        $printer->print(
            $spy,
            new DateTimeImmutable('2023-01-02'),
            new DateTimeImmutable('2023-01-02'),
        );

        static::assertSame(
            <<<OUTPUT
            2023.01.02 Monday » 20/0 » test-holiday (100)
                vacation
                sick
                2023-01-02 08:00:00 - 2023-01-02 12:00:00
            
            20 // 0

            OUTPUT,
            $spy->getBuffer(),
        );
    }
}
