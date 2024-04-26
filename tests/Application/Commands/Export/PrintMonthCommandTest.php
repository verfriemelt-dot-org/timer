<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Export;

use DateTimeImmutable;
use timer\Commands\Export\PrintMonthCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class PrintMonthCommandTest extends ApplicationTestCase
{
    public function test_month(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                PrintMonthCommand::class,
                ['12', '2023'],
            ),
        );

        static::assertSame(
            <<<OUTPUT
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
            
            0 // 168
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }

    public function test_default(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                PrintMonthCommand::class,
                [],
            ),
        );

        static::assertSame(
            <<<OUTPUT
            2023.12.01 Friday » 0/8
            2023.12.02 Saturday » 0/0
            2023.12.03 Sunday » 0/0
            2023.12.04 Monday » 0/8
            2023.12.05 Tuesday » 0/8
            2023.12.06 Wednesday » 0/8
            2023.12.07 Thursday » 0/8

            0 // 40
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }

    public function test_at_end_of_month(): void
    {
        $this->clock->set(new DateTimeImmutable('2023-12-31 00:00:00'));

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                PrintMonthCommand::class,
                [],
            ),
        );

        static::assertSame(
            <<<OUTPUT
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

            0 // 168
            
            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }
}
