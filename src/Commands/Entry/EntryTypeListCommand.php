<?php

declare(strict_types=1);

namespace timer\Commands\Entry;

use timer\Domain\EntryType;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('entry:list', 'prints out available entry types')]
final class EntryTypeListCommand extends AbstractCommand
{
    public function __construct(
    ) {}

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $output->writeLn('');

        $output->write('  type');
        $output->write(\str_repeat(' ', 25 - 4));
        $output->write('factor');
        $output->eol();
        $output->write('  ');
        $output->writeLn(\str_repeat('=', 25 + 4 + 2));

        foreach (EntryType::cases() as $type) {
            $output->write("  {$type->name}");
            $output->write(\str_repeat(' ', 25 - \mb_strlen($type->name)));
            $output->write((string) $type->getFactor());
            $output->eol();
        }

        $output->writeLn('');

        return ExitCode::Success;
    }
}
