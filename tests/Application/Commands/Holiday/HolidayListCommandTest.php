<?php

declare(strict_types=1);

namespace timer\tests\Application\Commands\Holiday;

use timer\Commands\Holiday\HolidayListCommand;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\tests\Application\ApplicationTestCase;
use verfriemelt\wrapped\_\Command\ExitCode;

final class HolidayListCommandTest extends ApplicationTestCase
{
    public function test(): void
    {
        $repo = $this->kernel->getContainer()->get(HolidayRepositoryInterface::class);
        static::assertInstanceOf(HolidayRepositoryInterface::class, $repo);

        $repo->add(new PublicHolidayDto(new DateDto('2023-01-01'), 'neujahr'));
        $repo->add(new PublicHolidayDto(new DateDto('2024-01-01'), 'neujahr'));
        static::assertSame(ExitCode::Success, $this->executeCommand(HolidayListCommand::class, ['2023']));

        static::assertSame(
            <<<OUTPUT
            2023-01-01 neujahr
            
            OUTPUT,
            $this->consoleSpy->getBuffer()
        );
    }
}
