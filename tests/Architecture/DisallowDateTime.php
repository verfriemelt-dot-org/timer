<?php

declare(strict_types=1);

namespace timer\tests\Architecture;

use DateTime;
use DateTimeImmutable;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class DisallowDateTime
{
    public function test_no_DateTime(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('\\timer'))
            ->excluding(Selector::inNamespace('\\timer\\tests'))
            ->shouldNotDependOn()
            ->classes(
                Selector::classname(DateTime::class),
            )
            ->because('you should use DateTimeImmutable instead')
        ;
    }

    public function test_no_DateTime_instanciation(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('\\timer'))
            ->excluding(Selector::inNamespace('\\timer\\tests'))
            ->shouldNotConstruct()
            ->classes(
                Selector::classname(DateTime::class),
                Selector::classname(DateTimeImmutable::class),
            )

            ->because('you should use psr/clock instead')
        ;
    }
}
