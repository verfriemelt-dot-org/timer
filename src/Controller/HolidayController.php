<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTimeImmutable;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Repository\HolidayRepositoryInterface;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Response\Response;

final class HolidayController extends Controller
{
    public function __construct(
        private readonly HolidayRepositoryInterface $holidayRepository,
        private readonly Console $console,
    ) {}

    public function handle_list(): Response
    {
        $holidays = $this->holidayRepository->all()->holidays;
        usort(
            $holidays,
            static fn (PublicHoliday $a, PublicHoliday $b): int => new DateTimeImmutable($a->date->day) <=> new DateTimeImmutable($b->date->day)
        );

        foreach ($holidays as $holiday) {
            $this->console->writeLn("{$holiday->date->day} {$holiday->name}");
        }

        return new Response();
    }

    public function handle_add(): Response
    {
        return new Response();
    }
}
