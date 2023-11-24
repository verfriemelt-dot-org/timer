<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTime;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use timer\Domain\Repository\CurrentWorkRepositoryInterface;
use timer\Domain\Repository\EntryRepositoryInterface;
use timer\Domain\TimeDiff;
use timer\Domain\WorkTimeCalculator;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Request\Request;
use verfriemelt\wrapped\_\Http\Response\Response;

class EntryController extends Controller
{
    public function __construct(
        private readonly EntryRepositoryInterface $entryRepository,
        private readonly WorkTimeCalculator $workTimeCalculator,
        private readonly CurrentWorkRepositoryInterface $currentWorkRepository,
        private readonly TimeDiff $timeDiff,
        private readonly Console $console,
    ) {}

    public function handle_index(): Response
    {
        return $this->handle_clock();
    }

    public function handle_cat(): Response
    {
        $path = \dirname(__FILE__, 2) . '/data/current.json';
        if (!\file_exists($path)) {
            $this->console->writeLn('not started');
            return new Response();
        }
        \var_dump(\file_get_contents($path));

        return new Response();
    }

    public function handle_sick(): Response
    {
        $this->entryRepository->add(
            new EntryDto(
                new DateDto((new DateTime())->format('Y-m-d')),
                type: EntryType::Sick,
            )
        );

        return new Response();
    }

    public function handle_vacation(): Response
    {
        $this->entryRepository->add(
            new EntryDto(
                new DateDto((new DateTime())->format('Y-m-d')),
                type: EntryType::Vacation,
            )
        );

        return new Response();
    }

    public function handle_reset(): Response
    {
        $path = \dirname(__FILE__, 3) . '/data/current.json';
        if (!\file_exists($path)) {
            $this->console->writeLn('not started');
            return new Response();
        }

        \var_dump(\file_get_contents($path));

        $this->console->writeLn('deleted');
        \unlink($path);

        return new Response();
    }

    public function handle_clock(): Response
    {
        $today = new DateTime();

        $entries = $this->entryRepository->getDay($today);
        $hours = $this->workTimeCalculator->getTotalWorkHours($entries)
            + $this->workTimeCalculator->getVacationHours($entries)
            + $this->workTimeCalculator->getSickHours($entries)
        ;

        $expected = $this->workTimeCalculator->expectedHours($today);

        if ($this->currentWorkRepository->has()) {
            $hours += $this->timeDiff->getInHours($this->currentWorkRepository->get()->till((new DateTime())->format('Y-m-d H:i:s')));
        }

        $hours = \number_format($hours, 2, '.');

        $this->console->writeLn("[{$hours} :: {$expected}]");

        return new Response();
    }

    public function handle_toggle(Request $request): Response
    {
        $timeString = $request->attributes()->get('args', 'now');

        if (!$this->currentWorkRepository->has()) {
            \var_dump($this->currentWorkRepository->toggle($timeString));
            return new Response();
        }

        $workTimeDto = $this->currentWorkRepository->toggle($timeString);

        $work = new EntryDto(
            new DateDto((new DateTime())->format('Y-m-d')),
            $workTimeDto
        );

        \var_dump($work);

        $this->entryRepository->add($work);

        return new Response();
    }
}
