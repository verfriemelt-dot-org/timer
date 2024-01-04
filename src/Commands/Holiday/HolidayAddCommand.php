<?php

declare(strict_types=1);

namespace timer\Commands\Holiday;

use Override;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\HolidayDto;
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
    private \verfriemelt\wrapped\_\Command\CommandArguments\Option $factor;

    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
    ) {}

    #[Override]
    public function configure(ArgvParser $argv): void
    {
        $this->date = new Argument('date');
        $this->name = new Argument('name', Argument::REQUIRED | Argument::VARIADIC);
        $this->factor = new \verfriemelt\wrapped\_\Command\CommandArguments\Option(
            'factor',
            \verfriemelt\wrapped\_\Command\CommandArguments\Option::EXPECTS_VALUE,
            description: 'used for half holidays provided by the company',
            short: 'f',
            default: '100'
        );

        $argv->addArguments($this->date, $this->name);
        $argv->addOptions($this->factor);
    }

    #[Override]
    public function execute(OutputInterface $output): ExitCode
    {
        $factor = (int) $this->factor->get();

        $this->holidayRepository->add(new HolidayDto(
            new DateDto($this->date->get() ?? throw new RuntimeException()),
            $this->name->get() ?? throw new RuntimeException(),
            $factor,
        ));

        return ExitCode::Success;
    }
}
