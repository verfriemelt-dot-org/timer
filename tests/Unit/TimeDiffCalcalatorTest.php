<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use timer\Domain\Clock;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\TimeDiffCalcalator;
use verfriemelt\wrapped\_\Clock\MockClock;

class TimeDiffCalcalatorTest extends TestCase
{
    public function test(): void
    {
        $diff = new TimeDiffCalcalator(new Clock(new MockClock(new DateTimeImmutable())));
        $dto = new WorkTimeDto(
            '2022-02-02 08:00:00',
            '2022-02-02 09:15:30',
        );

        static::assertSame((float) (3600 + 15 * 60 + 30), $diff->getInSeconds($dto));
        static::assertSame((float) (3600 + 15 * 60 + 30) / 3600, $diff->getInHours($dto));
    }
}
