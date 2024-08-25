<?php

declare(strict_types=1);

namespace timer\Commands\Hours;

use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\WorkHoursDto;
use timer\Domain\Repository\ExpectedHoursRepository;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\ExitCode;
use Override;

#[Command('hours:add', 'adds hours definitions')]
final class HoursAddCommand extends AbstractCommand
{
    public function __construct(
        private readonly ExpectedHoursRepository $expectedHoursRepository,
        private readonly HoursShowCommand $hoursShowCommand,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('start-date', description: 'start date of that ruleset'));
        $this->addArgument(new Argument('mon', description: 'monday hours'));
        $this->addArgument(new Argument('tue', description: 'tuesday hours'));
        $this->addArgument(new Argument('wed', description: 'wednesday hours'));
        $this->addArgument(new Argument('thu', description: 'thursday hours'));
        $this->addArgument(new Argument('fri', description: 'friday hours'));
        $this->addArgument(new Argument('sat', description: 'saturday hours'));
        $this->addArgument(new Argument('sun', description: 'sunday hours'));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $startDate = new DateDto((string) $input->getArgument('start-date')->get());

        $hoursDto = new ExpectedHoursDto(
            $startDate,
            new WorkHoursDto(
                (float) $input->getArgument('mon')->get(),
                (float) $input->getArgument('tue')->get(),
                (float) $input->getArgument('wed')->get(),
                (float) $input->getArgument('thu')->get(),
                (float) $input->getArgument('fri')->get(),
                (float) $input->getArgument('sat')->get(),
                (float) $input->getArgument('sun')->get(),
            ),
        );

        $this->expectedHoursRepository->add($hoursDto);

        return $this->hoursShowCommand->execute($input, $output);
    }
}
