<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use Override;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\PublicHolidayDto;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Cli\OutputInterface;
use verfriemelt\wrapped\_\Command\AbstractCommand;
use verfriemelt\wrapped\_\Command\Attributes\Command;
use verfriemelt\wrapped\_\Command\CommandArguments\Argument;
use verfriemelt\wrapped\_\Command\CommandArguments\ArgvParser;
use verfriemelt\wrapped\_\Command\ExitCode;
use RuntimeException;

#[Command('holiday:add', 'adds a new holiday')]
final class HolidayAddCommand extends AbstractCommand
{
    private Argument $date;
    private Argument $name;

    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $this->date = new Argument('date');
        $this->name = new Argument('name', Argument::REQUIRED | Argument::VARIADIC);

        $argv->addArguments($this->date, $this->name);
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $this->holidayRepository->add(new PublicHolidayDto(
            new DateDto($this->date->get() ?? throw new RuntimeException()),
            $this->name->get()  ?? throw new RuntimeException()
        ));

        return ExitCode::Success;
    }
}
