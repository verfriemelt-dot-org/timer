<?php

declare(strict_types=1);

namespace timer\Commands\Vacation;

use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('vacation:rules', 'print all rules')]
final class VacationRuleCommand extends AbstractCommand
{
    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        echo 'hi';

        return ExitCode::Success;
    }
}
