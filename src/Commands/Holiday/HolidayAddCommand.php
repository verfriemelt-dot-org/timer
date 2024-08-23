<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use Override;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\HolidayDto;
use timer\Domain\Repository\HolidayRepository;
use verfriemelt\wrapped\_\Cli\InputInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\Option;
use verfriemelt\wrapped\_\Command\ExitCode;
use RuntimeException;

#[Command('holiday:add', 'adds a new holiday')]
final class HolidayAddCommand extends AbstractCommand
{
    public function __construct(
        private readonly HolidayRepository $holidayRepository,
    ) {}

    #[Override]
    public function configure(): void
    {
        $this->addArgument(new Argument('date'));
        $this->addArgument(new Argument('name', Argument::REQUIRED | Argument::VARIADIC));
        $this->addOption(new Option(
            'factor',
            Option::EXPECTS_VALUE,
            description: 'used for half holidays provided by the company',
            short: 'f',
            default: '100',
        ));
    }

    #[Override]
    public function execute(InputInterface $input, OutputInterface $output): ExitCode
    {
        $factor = (int) $input->getOption('factor')->get();

        $this->holidayRepository->add(new HolidayDto(
            new DateDto($input->getArgument('date')->get() ?? throw new RuntimeException()),
            $input->getArgument('name')->get() ?? throw new RuntimeException(),
            $factor,
        ));

        return ExitCode::Success;
    }
}
