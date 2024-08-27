<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Export;

use timer\Commands\Hours\HoursAddCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class HoursAddCommandTest extends ApplicationTestCase
{
    public function test_single(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                HoursAddCommand::class,
                ['2023-04-01', '1', '2', '3', '4', '5', '6', '7'],
            ),
        );

        static::assertSame(
            <<<OUTPUT
            timer\Domain\Dto\ExpectedHoursDto Object
            (
                [from] => timer\Domain\Dto\DateDto Object
                    (
                        [day] => 2023-04-01
                    )
            
                [hours] => timer\Domain\Dto\WorkHoursDto Object
                    (
                        [monday] => 1
                        [tuesday] => 2
                        [wednesday] => 3
                        [thursday] => 4
                        [friday] => 5
                        [saturday] => 6
                        [sunday] => 7
                    )
            
            )

            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }

    public function test_multiple(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                HoursAddCommand::class,
                ['2023-04-01', '1', '2', '3', '4', '5', '6', '7'],
            ),
        );

        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                HoursAddCommand::class,
                ['2023-12-01', '11', '12', '13', '14', '15', '16', '17'],
            ),
        );

        static::assertSame(
            <<<OUTPUT
            timer\Domain\Dto\ExpectedHoursDto Object
            (
                [from] => timer\Domain\Dto\DateDto Object
                    (
                        [day] => 2023-12-01
                    )
            
                [hours] => timer\Domain\Dto\WorkHoursDto Object
                    (
                        [monday] => 11
                        [tuesday] => 12
                        [wednesday] => 13
                        [thursday] => 14
                        [friday] => 15
                        [saturday] => 16
                        [sunday] => 17
                    )
            
            )

            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }
}
