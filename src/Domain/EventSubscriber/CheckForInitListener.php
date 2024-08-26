<?php

declare(strict_types=1);

namespace timer\Domain\EventSubscriber;

use Closure;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\ExpectedHoursDto;
use timer\Domain\Dto\WorkHoursDto;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\ExpectedHoursRepository;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Command\Event\KernelPreCommandEvent;
use verfriemelt\wrapped\_\Events\EventInterface;
use verfriemelt\wrapped\_\Events\EventSubscriberInterface;

final class CheckForInitListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly ExpectedHoursRepository $hoursRepository,
        private readonly EntryRepository $entryRepository,
    ) {}

    public function on(EventInterface $event): ?Closure
    {
        return match (true) {
            $event instanceof KernelPreCommandEvent => $this->isInitialized(...),
            default => null,
        };
    }

    protected function isInitialized(KernelPreCommandEvent $event): KernelPreCommandEvent
    {
        $doSomething = false;

        if (!$this->entryRepository->initialized()) {
            $event->output->write('entry repo not initialized ... ');
            $this->entryRepository->initialize();
            $event->output->writeLn('created!');
        }

        if (!$this->hoursRepository->initialized()) {
            $event->output->writeLn('hours repo not initialized');

            $cli = new Console();
            $cli->writeLn('working hours starting from monday (like "8 8 8 5.5 5.5 0 0" - needs to be 7 values)?');

            $hours = $cli->read();
            $hours = array_map(static fn (string $i): float => (float) $i, explode(' ', $hours));

            $cli->writeLn('since when? like 2022-01-31?');
            $since = $cli->read();

            $hours = new ExpectedHoursDto(
                new DateDto($since),
                new WorkHoursDto(... $hours),
            );

            $this->hoursRepository->add($hours);
        }

        return $event;
    }
}
