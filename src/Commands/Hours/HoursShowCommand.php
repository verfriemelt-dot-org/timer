<?php

declare(strict_types=1);

namespace timer\Commands\Hours;

use timer\Domain\Repository\ExpectedHoursRepository;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('hours:show', 'dumps out hours definitions')]
final class HoursShowCommand extends AbstractCommand
{
    public function __construct(
        private readonly ExpectedHoursRepository $expectedHoursRepository,
    ) {}

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $output->write(print_r($this->expectedHoursRepository->getActive(), true));

        return ExitCode::Success;
    }
}
