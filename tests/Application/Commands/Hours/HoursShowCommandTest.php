<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Export;

use timer\Commands\Hours\HoursShowCommand;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

class HoursShowCommandTest extends ApplicationTestCase
{
    public function test_month(): void
    {
        static::assertSame(
            ExitCode::Success,
            $this->executeCommand(
                HoursShowCommand::class,
                [],
            ),
        );

        static::assertSame(
            <<<OUTPUT
            timer\Domain\Dto\ExpectedHoursDto Object
            (
                [from] => timer\Domain\Dto\DateDto Object
                    (
                        [day] => 1999-01-01
                    )
            
                [hours] => timer\Domain\Dto\WorkHoursDto Object
                    (
                        [monday] => 8
                        [tuesday] => 8
                        [wednesday] => 8
                        [thursday] => 8
                        [friday] => 8
                        [saturday] => 0
                        [sunday] => 0
                    )
            
            )

            OUTPUT,
            $this->consoleSpy->getBuffer(),
        );
    }
}
