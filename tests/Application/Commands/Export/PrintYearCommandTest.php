<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Export;

use timer\Commands\Export\PrintYearCommand;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

class PrintYearCommandTest extends ApplicationTestCase
{
    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->entryRepository->add(new EntryDto(new DateDto('2023-01-02'), type: EntryType::Vacation));

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-01-03'),
                EntryType::Work,
                new WorkTimeDto(
                    '2023-01-02 08:00:00',
                    '2023-01-02 12:00:00',
                ),
            ),
        );

        $this->entryRepository->add(
            new EntryDto(
                new DateDto('2023-01-03'),
                EntryType::Work,
                new WorkTimeDto(
                    '2023-01-02 14:00:00',
                    '2023-01-02 16:00:00',
                ),
            ),
        );
    }

    public function test_without_argument(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                PrintYearCommand::class,
                [],
            ),
        );

        static::assertSame(
            <<<OUTPUT
            2023.01.01 Sunday » 0/0
            2023.01.02 Monday » 8/8
                vacation
            2023.01.03 Tuesday » 6/8
                2023-01-02 08:00:00 - 2023-01-02 12:00:00
                2023-01-02 14:00:00 - 2023-01-02 16:00:00
            2023.01.04 Wednesday » 0/8
            2023.01.05 Thursday » 0/8
            2023.01.06 Friday » 0/8
            2023.01.07 Saturday » 0/0
            2023.01.08 Sunday » 0/0
            2023.01.09 Monday » 0/8
            2023.01.10 Tuesday » 0/8
            2023.01.11 Wednesday » 0/8
            2023.01.12 Thursday » 0/8
            2023.01.13 Friday » 0/8
            2023.01.14 Saturday » 0/0
            2023.01.15 Sunday » 0/0
            2023.01.16 Monday » 0/8
            2023.01.17 Tuesday » 0/8
            2023.01.18 Wednesday » 0/8
            2023.01.19 Thursday » 0/8
            2023.01.20 Friday » 0/8
            2023.01.21 Saturday » 0/0
            2023.01.22 Sunday » 0/0
            2023.01.23 Monday » 0/8
            2023.01.24 Tuesday » 0/8
            2023.01.25 Wednesday » 0/8
            2023.01.26 Thursday » 0/8
            2023.01.27 Friday » 0/8
            2023.01.28 Saturday » 0/0
            2023.01.29 Sunday » 0/0
            2023.01.30 Monday » 0/8
            2023.01.31 Tuesday » 0/8
            2023.02.01 Wednesday » 0/8
            2023.02.02 Thursday » 0/8
            2023.02.03 Friday » 0/8
            2023.02.04 Saturday » 0/0
            2023.02.05 Sunday » 0/0
            2023.02.06 Monday » 0/8
            2023.02.07 Tuesday » 0/8
            2023.02.08 Wednesday » 0/8
            2023.02.09 Thursday » 0/8
            2023.02.10 Friday » 0/8
            2023.02.11 Saturday » 0/0
            2023.02.12 Sunday » 0/0
            2023.02.13 Monday » 0/8
            2023.02.14 Tuesday » 0/8
            2023.02.15 Wednesday » 0/8
            2023.02.16 Thursday » 0/8
            2023.02.17 Friday » 0/8
            2023.02.18 Saturday » 0/0
            2023.02.19 Sunday » 0/0
            2023.02.20 Monday » 0/8
            2023.02.21 Tuesday » 0/8
            2023.02.22 Wednesday » 0/8
            2023.02.23 Thursday » 0/8
            2023.02.24 Friday » 0/8
            2023.02.25 Saturday » 0/0
            2023.02.26 Sunday » 0/0
            2023.02.27 Monday » 0/8
            2023.02.28 Tuesday » 0/8
            2023.03.01 Wednesday » 0/8
            2023.03.02 Thursday » 0/8
            2023.03.03 Friday » 0/8
            2023.03.04 Saturday » 0/0
            2023.03.05 Sunday » 0/0
            2023.03.06 Monday » 0/8
            2023.03.07 Tuesday » 0/8
            2023.03.08 Wednesday » 0/8
            2023.03.09 Thursday » 0/8
            2023.03.10 Friday » 0/8
            2023.03.11 Saturday » 0/0
            2023.03.12 Sunday » 0/0
            2023.03.13 Monday » 0/8
            2023.03.14 Tuesday » 0/8
            2023.03.15 Wednesday » 0/8
            2023.03.16 Thursday » 0/8
            2023.03.17 Friday » 0/8
            2023.03.18 Saturday » 0/0
            2023.03.19 Sunday » 0/0
            2023.03.20 Monday » 0/8
            2023.03.21 Tuesday » 0/8
            2023.03.22 Wednesday » 0/8
            2023.03.23 Thursday » 0/8
            2023.03.24 Friday » 0/8
            2023.03.25 Saturday » 0/0
            2023.03.26 Sunday » 0/0
            2023.03.27 Monday » 0/8
            2023.03.28 Tuesday » 0/8
            2023.03.29 Wednesday » 0/8
            2023.03.30 Thursday » 0/8
            2023.03.31 Friday » 0/8
            2023.04.01 Saturday » 0/0
            2023.04.02 Sunday » 0/0
            2023.04.03 Monday » 0/8
            2023.04.04 Tuesday » 0/8
            2023.04.05 Wednesday » 0/8
            2023.04.06 Thursday » 0/8
            2023.04.07 Friday » 0/8
            2023.04.08 Saturday » 0/0
            2023.04.09 Sunday » 0/0
            2023.04.10 Monday » 0/8
            2023.04.11 Tuesday » 0/8
            2023.04.12 Wednesday » 0/8
            2023.04.13 Thursday » 0/8
            2023.04.14 Friday » 0/8
            2023.04.15 Saturday » 0/0
            2023.04.16 Sunday » 0/0
            2023.04.17 Monday » 0/8
            2023.04.18 Tuesday » 0/8
            2023.04.19 Wednesday » 0/8
            2023.04.20 Thursday » 0/8
            2023.04.21 Friday » 0/8
            2023.04.22 Saturday » 0/0
            2023.04.23 Sunday » 0/0
            2023.04.24 Monday » 0/8
            2023.04.25 Tuesday » 0/8
            2023.04.26 Wednesday » 0/8
            2023.04.27 Thursday » 0/8
            2023.04.28 Friday » 0/8
            2023.04.29 Saturday » 0/0
            2023.04.30 Sunday » 0/0
            2023.05.01 Monday » 0/8
            2023.05.02 Tuesday » 0/8
            2023.05.03 Wednesday » 0/8
            2023.05.04 Thursday » 0/8
            2023.05.05 Friday » 0/8
            2023.05.06 Saturday » 0/0
            2023.05.07 Sunday » 0/0
            2023.05.08 Monday » 0/8
            2023.05.09 Tuesday » 0/8
            2023.05.10 Wednesday » 0/8
            2023.05.11 Thursday » 0/8
            2023.05.12 Friday » 0/8
            2023.05.13 Saturday » 0/0
            2023.05.14 Sunday » 0/0
            2023.05.15 Monday » 0/8
            2023.05.16 Tuesday » 0/8
            2023.05.17 Wednesday » 0/8
            2023.05.18 Thursday » 0/8
            2023.05.19 Friday » 0/8
            2023.05.20 Saturday » 0/0
            2023.05.21 Sunday » 0/0
            2023.05.22 Monday » 0/8
            2023.05.23 Tuesday » 0/8
            2023.05.24 Wednesday » 0/8
            2023.05.25 Thursday » 0/8
            2023.05.26 Friday » 0/8
            2023.05.27 Saturday » 0/0
            2023.05.28 Sunday » 0/0
            2023.05.29 Monday » 0/8
            2023.05.30 Tuesday » 0/8
            2023.05.31 Wednesday » 0/8
            2023.06.01 Thursday » 0/8
            2023.06.02 Friday » 0/8
            2023.06.03 Saturday » 0/0
            2023.06.04 Sunday » 0/0
            2023.06.05 Monday » 0/8
            2023.06.06 Tuesday » 0/8
            2023.06.07 Wednesday » 0/8
            2023.06.08 Thursday » 0/8
            2023.06.09 Friday » 0/8
            2023.06.10 Saturday » 0/0
            2023.06.11 Sunday » 0/0
            2023.06.12 Monday » 0/8
            2023.06.13 Tuesday » 0/8
            2023.06.14 Wednesday » 0/8
            2023.06.15 Thursday » 0/8
            2023.06.16 Friday » 0/8
            2023.06.17 Saturday » 0/0
            2023.06.18 Sunday » 0/0
            2023.06.19 Monday » 0/8
            2023.06.20 Tuesday » 0/8
            2023.06.21 Wednesday » 0/8
            2023.06.22 Thursday » 0/8
            2023.06.23 Friday » 0/8
            2023.06.24 Saturday » 0/0
            2023.06.25 Sunday » 0/0
            2023.06.26 Monday » 0/8
            2023.06.27 Tuesday » 0/8
            2023.06.28 Wednesday » 0/8
            2023.06.29 Thursday » 0/8
            2023.06.30 Friday » 0/8
            2023.07.01 Saturday » 0/0
            2023.07.02 Sunday » 0/0
            2023.07.03 Monday » 0/8
            2023.07.04 Tuesday » 0/8
            2023.07.05 Wednesday » 0/8
            2023.07.06 Thursday » 0/8
            2023.07.07 Friday » 0/8
            2023.07.08 Saturday » 0/0
            2023.07.09 Sunday » 0/0
            2023.07.10 Monday » 0/8
            2023.07.11 Tuesday » 0/8
            2023.07.12 Wednesday » 0/8
            2023.07.13 Thursday » 0/8
            2023.07.14 Friday » 0/8
            2023.07.15 Saturday » 0/0
            2023.07.16 Sunday » 0/0
            2023.07.17 Monday » 0/8
            2023.07.18 Tuesday » 0/8
            2023.07.19 Wednesday » 0/8
            2023.07.20 Thursday » 0/8
            2023.07.21 Friday » 0/8
            2023.07.22 Saturday » 0/0
            2023.07.23 Sunday » 0/0
            2023.07.24 Monday » 0/8
            2023.07.25 Tuesday » 0/8
            2023.07.26 Wednesday » 0/8
            2023.07.27 Thursday » 0/8
            2023.07.28 Friday » 0/8
            2023.07.29 Saturday » 0/0
            2023.07.30 Sunday » 0/0
            2023.07.31 Monday » 0/8
            2023.08.01 Tuesday » 0/8
            2023.08.02 Wednesday » 0/8
            2023.08.03 Thursday » 0/8
            2023.08.04 Friday » 0/8
            2023.08.05 Saturday » 0/0
            2023.08.06 Sunday » 0/0
            2023.08.07 Monday » 0/8
            2023.08.08 Tuesday » 0/8
            2023.08.09 Wednesday » 0/8
            2023.08.10 Thursday » 0/8
            2023.08.11 Friday » 0/8
            2023.08.12 Saturday » 0/0
            2023.08.13 Sunday » 0/0
            2023.08.14 Monday » 0/8
            2023.08.15 Tuesday » 0/8
            2023.08.16 Wednesday » 0/8
            2023.08.17 Thursday » 0/8
            2023.08.18 Friday » 0/8
            2023.08.19 Saturday » 0/0
            2023.08.20 Sunday » 0/0
            2023.08.21 Monday » 0/8
            2023.08.22 Tuesday » 0/8
            2023.08.23 Wednesday » 0/8
            2023.08.24 Thursday » 0/8
            2023.08.25 Friday » 0/8
            2023.08.26 Saturday » 0/0
            2023.08.27 Sunday » 0/0
            2023.08.28 Monday » 0/8
            2023.08.29 Tuesday » 0/8
            2023.08.30 Wednesday » 0/8
            2023.08.31 Thursday » 0/8
            2023.09.01 Friday » 0/8
            2023.09.02 Saturday » 0/0
            2023.09.03 Sunday » 0/0
            2023.09.04 Monday » 0/8
            2023.09.05 Tuesday » 0/8
            2023.09.06 Wednesday » 0/8
            2023.09.07 Thursday » 0/8
            2023.09.08 Friday » 0/8
            2023.09.09 Saturday » 0/0
            2023.09.10 Sunday » 0/0
            2023.09.11 Monday » 0/8
            2023.09.12 Tuesday » 0/8
            2023.09.13 Wednesday » 0/8
            2023.09.14 Thursday » 0/8
            2023.09.15 Friday » 0/8
            2023.09.16 Saturday » 0/0
            2023.09.17 Sunday » 0/0
            2023.09.18 Monday » 0/8
            2023.09.19 Tuesday » 0/8
            2023.09.20 Wednesday » 0/8
            2023.09.21 Thursday » 0/8
            2023.09.22 Friday » 0/8
            2023.09.23 Saturday » 0/0
            2023.09.24 Sunday » 0/0
            2023.09.25 Monday » 0/8
            2023.09.26 Tuesday » 0/8
            2023.09.27 Wednesday » 0/8
            2023.09.28 Thursday » 0/8
            2023.09.29 Friday » 0/8
            2023.09.30 Saturday » 0/0
            2023.10.01 Sunday » 0/0
            2023.10.02 Monday » 0/8
            2023.10.03 Tuesday » 0/8
            2023.10.04 Wednesday » 0/8
            2023.10.05 Thursday » 0/8
            2023.10.06 Friday » 0/8
            2023.10.07 Saturday » 0/0
            2023.10.08 Sunday » 0/0
            2023.10.09 Monday » 0/8
            2023.10.10 Tuesday » 0/8
            2023.10.11 Wednesday » 0/8
            2023.10.12 Thursday » 0/8
            2023.10.13 Friday » 0/8
            2023.10.14 Saturday » 0/0
            2023.10.15 Sunday » 0/0
            2023.10.16 Monday » 0/8
            2023.10.17 Tuesday » 0/8
            2023.10.18 Wednesday » 0/8
            2023.10.19 Thursday » 0/8
            2023.10.20 Friday » 0/8
            2023.10.21 Saturday » 0/0
            2023.10.22 Sunday » 0/0
            2023.10.23 Monday » 0/8
            2023.10.24 Tuesday » 0/8
            2023.10.25 Wednesday » 0/8
            2023.10.26 Thursday » 0/8
            2023.10.27 Friday » 0/8
            2023.10.28 Saturday » 0/0
            2023.10.29 Sunday » 0/0
            2023.10.30 Monday » 0/8
            2023.10.31 Tuesday » 0/8
            2023.11.01 Wednesday » 0/8
            2023.11.02 Thursday » 0/8
            2023.11.03 Friday » 0/8
            2023.11.04 Saturday » 0/0
            2023.11.05 Sunday » 0/0
            2023.11.06 Monday » 0/8
            2023.11.07 Tuesday » 0/8
            2023.11.08 Wednesday » 0/8
            2023.11.09 Thursday » 0/8
            2023.11.10 Friday » 0/8
            2023.11.11 Saturday » 0/0
            2023.11.12 Sunday » 0/0
            2023.11.13 Monday » 0/8
            2023.11.14 Tuesday » 0/8
            2023.11.15 Wednesday » 0/8
            2023.11.16 Thursday » 0/8
            2023.11.17 Friday » 0/8
            2023.11.18 Saturday » 0/0
            2023.11.19 Sunday » 0/0
            2023.11.20 Monday » 0/8
            2023.11.21 Tuesday » 0/8
            2023.11.22 Wednesday » 0/8
            2023.11.23 Thursday » 0/8
            2023.11.24 Friday » 0/8
            2023.11.25 Saturday » 0/0
            2023.11.26 Sunday » 0/0
            2023.11.27 Monday » 0/8
            2023.11.28 Tuesday » 0/8
            2023.11.29 Wednesday » 0/8
            2023.11.30 Thursday » 0/8
            2023.12.01 Friday » 0/8
            2023.12.02 Saturday » 0/0
            2023.12.03 Sunday » 0/0
            2023.12.04 Monday » 0/8
            2023.12.05 Tuesday » 0/8
            2023.12.06 Wednesday » 0/8
            2023.12.07 Thursday » 0/8
            2023.12.08 Friday » 0/8
            2023.12.09 Saturday » 0/0
            2023.12.10 Sunday » 0/0
            2023.12.11 Monday » 0/8
            2023.12.12 Tuesday » 0/8
            2023.12.13 Wednesday » 0/8
            2023.12.14 Thursday » 0/8
            2023.12.15 Friday » 0/8
            2023.12.16 Saturday » 0/0
            2023.12.17 Sunday » 0/0
            2023.12.18 Monday » 0/8
            2023.12.19 Tuesday » 0/8
            2023.12.20 Wednesday » 0/8
            2023.12.21 Thursday » 0/8
            2023.12.22 Friday » 0/8
            2023.12.23 Saturday » 0/0
            2023.12.24 Sunday » 0/0
            2023.12.25 Monday » 0/8
            2023.12.26 Tuesday » 0/8
            2023.12.27 Wednesday » 0/8
            2023.12.28 Thursday » 0/8
            2023.12.29 Friday » 0/8
            2023.12.30 Saturday » 0/0
            2023.12.31 Sunday » 0/0
            
            14 // 2080
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }

    public function test_with_argument(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                PrintYearCommand::class,
                ['2022'],
            ),
        );

        static::assertSame(
            <<<OUTPUT
            2022.01.01 Saturday » 0/0
            2022.01.02 Sunday » 0/0
            2022.01.03 Monday » 0/8
            2022.01.04 Tuesday » 0/8
            2022.01.05 Wednesday » 0/8
            2022.01.06 Thursday » 0/8
            2022.01.07 Friday » 0/8
            2022.01.08 Saturday » 0/0
            2022.01.09 Sunday » 0/0
            2022.01.10 Monday » 0/8
            2022.01.11 Tuesday » 0/8
            2022.01.12 Wednesday » 0/8
            2022.01.13 Thursday » 0/8
            2022.01.14 Friday » 0/8
            2022.01.15 Saturday » 0/0
            2022.01.16 Sunday » 0/0
            2022.01.17 Monday » 0/8
            2022.01.18 Tuesday » 0/8
            2022.01.19 Wednesday » 0/8
            2022.01.20 Thursday » 0/8
            2022.01.21 Friday » 0/8
            2022.01.22 Saturday » 0/0
            2022.01.23 Sunday » 0/0
            2022.01.24 Monday » 0/8
            2022.01.25 Tuesday » 0/8
            2022.01.26 Wednesday » 0/8
            2022.01.27 Thursday » 0/8
            2022.01.28 Friday » 0/8
            2022.01.29 Saturday » 0/0
            2022.01.30 Sunday » 0/0
            2022.01.31 Monday » 0/8
            2022.02.01 Tuesday » 0/8
            2022.02.02 Wednesday » 0/8
            2022.02.03 Thursday » 0/8
            2022.02.04 Friday » 0/8
            2022.02.05 Saturday » 0/0
            2022.02.06 Sunday » 0/0
            2022.02.07 Monday » 0/8
            2022.02.08 Tuesday » 0/8
            2022.02.09 Wednesday » 0/8
            2022.02.10 Thursday » 0/8
            2022.02.11 Friday » 0/8
            2022.02.12 Saturday » 0/0
            2022.02.13 Sunday » 0/0
            2022.02.14 Monday » 0/8
            2022.02.15 Tuesday » 0/8
            2022.02.16 Wednesday » 0/8
            2022.02.17 Thursday » 0/8
            2022.02.18 Friday » 0/8
            2022.02.19 Saturday » 0/0
            2022.02.20 Sunday » 0/0
            2022.02.21 Monday » 0/8
            2022.02.22 Tuesday » 0/8
            2022.02.23 Wednesday » 0/8
            2022.02.24 Thursday » 0/8
            2022.02.25 Friday » 0/8
            2022.02.26 Saturday » 0/0
            2022.02.27 Sunday » 0/0
            2022.02.28 Monday » 0/8
            2022.03.01 Tuesday » 0/8
            2022.03.02 Wednesday » 0/8
            2022.03.03 Thursday » 0/8
            2022.03.04 Friday » 0/8
            2022.03.05 Saturday » 0/0
            2022.03.06 Sunday » 0/0
            2022.03.07 Monday » 0/8
            2022.03.08 Tuesday » 0/8
            2022.03.09 Wednesday » 0/8
            2022.03.10 Thursday » 0/8
            2022.03.11 Friday » 0/8
            2022.03.12 Saturday » 0/0
            2022.03.13 Sunday » 0/0
            2022.03.14 Monday » 0/8
            2022.03.15 Tuesday » 0/8
            2022.03.16 Wednesday » 0/8
            2022.03.17 Thursday » 0/8
            2022.03.18 Friday » 0/8
            2022.03.19 Saturday » 0/0
            2022.03.20 Sunday » 0/0
            2022.03.21 Monday » 0/8
            2022.03.22 Tuesday » 0/8
            2022.03.23 Wednesday » 0/8
            2022.03.24 Thursday » 0/8
            2022.03.25 Friday » 0/8
            2022.03.26 Saturday » 0/0
            2022.03.27 Sunday » 0/0
            2022.03.28 Monday » 0/8
            2022.03.29 Tuesday » 0/8
            2022.03.30 Wednesday » 0/8
            2022.03.31 Thursday » 0/8
            2022.04.01 Friday » 0/8
            2022.04.02 Saturday » 0/0
            2022.04.03 Sunday » 0/0
            2022.04.04 Monday » 0/8
            2022.04.05 Tuesday » 0/8
            2022.04.06 Wednesday » 0/8
            2022.04.07 Thursday » 0/8
            2022.04.08 Friday » 0/8
            2022.04.09 Saturday » 0/0
            2022.04.10 Sunday » 0/0
            2022.04.11 Monday » 0/8
            2022.04.12 Tuesday » 0/8
            2022.04.13 Wednesday » 0/8
            2022.04.14 Thursday » 0/8
            2022.04.15 Friday » 0/8
            2022.04.16 Saturday » 0/0
            2022.04.17 Sunday » 0/0
            2022.04.18 Monday » 0/8
            2022.04.19 Tuesday » 0/8
            2022.04.20 Wednesday » 0/8
            2022.04.21 Thursday » 0/8
            2022.04.22 Friday » 0/8
            2022.04.23 Saturday » 0/0
            2022.04.24 Sunday » 0/0
            2022.04.25 Monday » 0/8
            2022.04.26 Tuesday » 0/8
            2022.04.27 Wednesday » 0/8
            2022.04.28 Thursday » 0/8
            2022.04.29 Friday » 0/8
            2022.04.30 Saturday » 0/0
            2022.05.01 Sunday » 0/0
            2022.05.02 Monday » 0/8
            2022.05.03 Tuesday » 0/8
            2022.05.04 Wednesday » 0/8
            2022.05.05 Thursday » 0/8
            2022.05.06 Friday » 0/8
            2022.05.07 Saturday » 0/0
            2022.05.08 Sunday » 0/0
            2022.05.09 Monday » 0/8
            2022.05.10 Tuesday » 0/8
            2022.05.11 Wednesday » 0/8
            2022.05.12 Thursday » 0/8
            2022.05.13 Friday » 0/8
            2022.05.14 Saturday » 0/0
            2022.05.15 Sunday » 0/0
            2022.05.16 Monday » 0/8
            2022.05.17 Tuesday » 0/8
            2022.05.18 Wednesday » 0/8
            2022.05.19 Thursday » 0/8
            2022.05.20 Friday » 0/8
            2022.05.21 Saturday » 0/0
            2022.05.22 Sunday » 0/0
            2022.05.23 Monday » 0/8
            2022.05.24 Tuesday » 0/8
            2022.05.25 Wednesday » 0/8
            2022.05.26 Thursday » 0/8
            2022.05.27 Friday » 0/8
            2022.05.28 Saturday » 0/0
            2022.05.29 Sunday » 0/0
            2022.05.30 Monday » 0/8
            2022.05.31 Tuesday » 0/8
            2022.06.01 Wednesday » 0/8
            2022.06.02 Thursday » 0/8
            2022.06.03 Friday » 0/8
            2022.06.04 Saturday » 0/0
            2022.06.05 Sunday » 0/0
            2022.06.06 Monday » 0/8
            2022.06.07 Tuesday » 0/8
            2022.06.08 Wednesday » 0/8
            2022.06.09 Thursday » 0/8
            2022.06.10 Friday » 0/8
            2022.06.11 Saturday » 0/0
            2022.06.12 Sunday » 0/0
            2022.06.13 Monday » 0/8
            2022.06.14 Tuesday » 0/8
            2022.06.15 Wednesday » 0/8
            2022.06.16 Thursday » 0/8
            2022.06.17 Friday » 0/8
            2022.06.18 Saturday » 0/0
            2022.06.19 Sunday » 0/0
            2022.06.20 Monday » 0/8
            2022.06.21 Tuesday » 0/8
            2022.06.22 Wednesday » 0/8
            2022.06.23 Thursday » 0/8
            2022.06.24 Friday » 0/8
            2022.06.25 Saturday » 0/0
            2022.06.26 Sunday » 0/0
            2022.06.27 Monday » 0/8
            2022.06.28 Tuesday » 0/8
            2022.06.29 Wednesday » 0/8
            2022.06.30 Thursday » 0/8
            2022.07.01 Friday » 0/8
            2022.07.02 Saturday » 0/0
            2022.07.03 Sunday » 0/0
            2022.07.04 Monday » 0/8
            2022.07.05 Tuesday » 0/8
            2022.07.06 Wednesday » 0/8
            2022.07.07 Thursday » 0/8
            2022.07.08 Friday » 0/8
            2022.07.09 Saturday » 0/0
            2022.07.10 Sunday » 0/0
            2022.07.11 Monday » 0/8
            2022.07.12 Tuesday » 0/8
            2022.07.13 Wednesday » 0/8
            2022.07.14 Thursday » 0/8
            2022.07.15 Friday » 0/8
            2022.07.16 Saturday » 0/0
            2022.07.17 Sunday » 0/0
            2022.07.18 Monday » 0/8
            2022.07.19 Tuesday » 0/8
            2022.07.20 Wednesday » 0/8
            2022.07.21 Thursday » 0/8
            2022.07.22 Friday » 0/8
            2022.07.23 Saturday » 0/0
            2022.07.24 Sunday » 0/0
            2022.07.25 Monday » 0/8
            2022.07.26 Tuesday » 0/8
            2022.07.27 Wednesday » 0/8
            2022.07.28 Thursday » 0/8
            2022.07.29 Friday » 0/8
            2022.07.30 Saturday » 0/0
            2022.07.31 Sunday » 0/0
            2022.08.01 Monday » 0/8
            2022.08.02 Tuesday » 0/8
            2022.08.03 Wednesday » 0/8
            2022.08.04 Thursday » 0/8
            2022.08.05 Friday » 0/8
            2022.08.06 Saturday » 0/0
            2022.08.07 Sunday » 0/0
            2022.08.08 Monday » 0/8
            2022.08.09 Tuesday » 0/8
            2022.08.10 Wednesday » 0/8
            2022.08.11 Thursday » 0/8
            2022.08.12 Friday » 0/8
            2022.08.13 Saturday » 0/0
            2022.08.14 Sunday » 0/0
            2022.08.15 Monday » 0/8
            2022.08.16 Tuesday » 0/8
            2022.08.17 Wednesday » 0/8
            2022.08.18 Thursday » 0/8
            2022.08.19 Friday » 0/8
            2022.08.20 Saturday » 0/0
            2022.08.21 Sunday » 0/0
            2022.08.22 Monday » 0/8
            2022.08.23 Tuesday » 0/8
            2022.08.24 Wednesday » 0/8
            2022.08.25 Thursday » 0/8
            2022.08.26 Friday » 0/8
            2022.08.27 Saturday » 0/0
            2022.08.28 Sunday » 0/0
            2022.08.29 Monday » 0/8
            2022.08.30 Tuesday » 0/8
            2022.08.31 Wednesday » 0/8
            2022.09.01 Thursday » 0/8
            2022.09.02 Friday » 0/8
            2022.09.03 Saturday » 0/0
            2022.09.04 Sunday » 0/0
            2022.09.05 Monday » 0/8
            2022.09.06 Tuesday » 0/8
            2022.09.07 Wednesday » 0/8
            2022.09.08 Thursday » 0/8
            2022.09.09 Friday » 0/8
            2022.09.10 Saturday » 0/0
            2022.09.11 Sunday » 0/0
            2022.09.12 Monday » 0/8
            2022.09.13 Tuesday » 0/8
            2022.09.14 Wednesday » 0/8
            2022.09.15 Thursday » 0/8
            2022.09.16 Friday » 0/8
            2022.09.17 Saturday » 0/0
            2022.09.18 Sunday » 0/0
            2022.09.19 Monday » 0/8
            2022.09.20 Tuesday » 0/8
            2022.09.21 Wednesday » 0/8
            2022.09.22 Thursday » 0/8
            2022.09.23 Friday » 0/8
            2022.09.24 Saturday » 0/0
            2022.09.25 Sunday » 0/0
            2022.09.26 Monday » 0/8
            2022.09.27 Tuesday » 0/8
            2022.09.28 Wednesday » 0/8
            2022.09.29 Thursday » 0/8
            2022.09.30 Friday » 0/8
            2022.10.01 Saturday » 0/0
            2022.10.02 Sunday » 0/0
            2022.10.03 Monday » 0/8
            2022.10.04 Tuesday » 0/8
            2022.10.05 Wednesday » 0/8
            2022.10.06 Thursday » 0/8
            2022.10.07 Friday » 0/8
            2022.10.08 Saturday » 0/0
            2022.10.09 Sunday » 0/0
            2022.10.10 Monday » 0/8
            2022.10.11 Tuesday » 0/8
            2022.10.12 Wednesday » 0/8
            2022.10.13 Thursday » 0/8
            2022.10.14 Friday » 0/8
            2022.10.15 Saturday » 0/0
            2022.10.16 Sunday » 0/0
            2022.10.17 Monday » 0/8
            2022.10.18 Tuesday » 0/8
            2022.10.19 Wednesday » 0/8
            2022.10.20 Thursday » 0/8
            2022.10.21 Friday » 0/8
            2022.10.22 Saturday » 0/0
            2022.10.23 Sunday » 0/0
            2022.10.24 Monday » 0/8
            2022.10.25 Tuesday » 0/8
            2022.10.26 Wednesday » 0/8
            2022.10.27 Thursday » 0/8
            2022.10.28 Friday » 0/8
            2022.10.29 Saturday » 0/0
            2022.10.30 Sunday » 0/0
            2022.10.31 Monday » 0/8
            2022.11.01 Tuesday » 0/8
            2022.11.02 Wednesday » 0/8
            2022.11.03 Thursday » 0/8
            2022.11.04 Friday » 0/8
            2022.11.05 Saturday » 0/0
            2022.11.06 Sunday » 0/0
            2022.11.07 Monday » 0/8
            2022.11.08 Tuesday » 0/8
            2022.11.09 Wednesday » 0/8
            2022.11.10 Thursday » 0/8
            2022.11.11 Friday » 0/8
            2022.11.12 Saturday » 0/0
            2022.11.13 Sunday » 0/0
            2022.11.14 Monday » 0/8
            2022.11.15 Tuesday » 0/8
            2022.11.16 Wednesday » 0/8
            2022.11.17 Thursday » 0/8
            2022.11.18 Friday » 0/8
            2022.11.19 Saturday » 0/0
            2022.11.20 Sunday » 0/0
            2022.11.21 Monday » 0/8
            2022.11.22 Tuesday » 0/8
            2022.11.23 Wednesday » 0/8
            2022.11.24 Thursday » 0/8
            2022.11.25 Friday » 0/8
            2022.11.26 Saturday » 0/0
            2022.11.27 Sunday » 0/0
            2022.11.28 Monday » 0/8
            2022.11.29 Tuesday » 0/8
            2022.11.30 Wednesday » 0/8
            2022.12.01 Thursday » 0/8
            2022.12.02 Friday » 0/8
            2022.12.03 Saturday » 0/0
            2022.12.04 Sunday » 0/0
            2022.12.05 Monday » 0/8
            2022.12.06 Tuesday » 0/8
            2022.12.07 Wednesday » 0/8
            2022.12.08 Thursday » 0/8
            2022.12.09 Friday » 0/8
            2022.12.10 Saturday » 0/0
            2022.12.11 Sunday » 0/0
            2022.12.12 Monday » 0/8
            2022.12.13 Tuesday » 0/8
            2022.12.14 Wednesday » 0/8
            2022.12.15 Thursday » 0/8
            2022.12.16 Friday » 0/8
            2022.12.17 Saturday » 0/0
            2022.12.18 Sunday » 0/0
            2022.12.19 Monday » 0/8
            2022.12.20 Tuesday » 0/8
            2022.12.21 Wednesday » 0/8
            2022.12.22 Thursday » 0/8
            2022.12.23 Friday » 0/8
            2022.12.24 Saturday » 0/0
            2022.12.25 Sunday » 0/0
            2022.12.26 Monday » 0/8
            2022.12.27 Tuesday » 0/8
            2022.12.28 Wednesday » 0/8
            2022.12.29 Thursday » 0/8
            2022.12.30 Friday » 0/8
            2022.12.31 Saturday » 0/0
            
            0 // 2080
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }
}
