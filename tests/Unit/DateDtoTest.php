<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use timer\Domain\Dto\DateDto;

class DateDtoTest extends TestCase
{
    public function test(): void
    {
        static::expectNotToPerformAssertions();
        new DateDto('2000-01-01');
    }

    public function test_for_illegal_date(): void
    {
        static::expectException(RuntimeException::class);
        new DateDto('foo');
    }
}
