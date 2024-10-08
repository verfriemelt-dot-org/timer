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
}
