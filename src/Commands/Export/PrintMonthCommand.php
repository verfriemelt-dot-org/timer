<?php

declare(strict_types=1);

namespace timer\Commands\Export;

use DateTimeImmutable;
use timer\Domain\Print\PrettyPrinter;
use verfriemelt\wrapped\_\Cli\Argument\CommandOptions;
use verfriemelt\wrapped\_\Cli\Argument\Option;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('export:text:month')]
final readonly class PrintMonthCommand extends AbstractCommand
{
    public function __construct(
        private PrettyPrinter $print,
        private CommandOptions $argvParser,
    ) {}

    #[Override]
    public function configure(CommandOptions $argv): void
    {
        $argv->addOptions(new Option(
            'test',
            Option::REQUIRED
        ));
    }

    #[Override]
    public function execute(Console $console): ExitCode
    {
        $asInt = static function (mixed $i): int {
            assert(\is_string($i));
            return (int) $i;
        };

        $month = $asInt($console->getArgv()->get(2, (new DateTimeImmutable())->format('m')));
        $year = $asInt($console->getArgv()->get(3, (new DateTimeImmutable())->format('Y')));

        $today = new DateTimeImmutable();
        $start = new DateTimeImmutable("{$year}-{$month}-01");
        $end = $start->modify('last day of this month');

        if ($console->getArgv()->hasNot(2) && $start < $today && $end > $today) {
            $end = new DateTimeImmutable('yesterday');
        }

        $this->print->print(
            $start,
            $end
        );

        return ExitCode::Success;
    }
}
