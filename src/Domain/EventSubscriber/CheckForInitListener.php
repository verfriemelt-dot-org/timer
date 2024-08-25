<?php

declare(strict_types=1);

namespace timer\Domain\EventSubscriber;

use Closure;
use timer\Domain\Repository\EntryRepository;
use timer\Domain\Repository\ExpectedHoursRepository;
use verfriemelt\wrapped\_\Command\Event\KernelPreCommandEvent;
use verfriemelt\wrapped\_\DI\ContainerInterface;
use verfriemelt\wrapped\_\Events\EventInterface;
use verfriemelt\wrapped\_\Events\EventSubscriberInterface;

final class CheckForInitListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly ExpectedHoursRepository $hoursRepository,
        private readonly EntryRepository $entryRepository,
        private readonly ContainerInterface $container,
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
        if (!$this->hoursRepository->initialized()) {
            $event->output->writeLn('hours repo initialized');
        }

        if (!$this->entryRepository->initialized()) {
            $event->output->writeLn('entry repo initialized');
        }

        return $event;
    }
}
