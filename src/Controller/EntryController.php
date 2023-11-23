<?php

declare(strict_types=1);

namespace timer\Controller;

use DateTime;
use timer\Domain\Dto\DateDto;
use timer\Domain\Dto\EntryDto;
use timer\Domain\Dto\WorkTimeDto;
use timer\Domain\EntryType;
use timer\Repository\EntryRepository;
use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\Controller\Controller;
use verfriemelt\wrapped\_\Http\Response\Response;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;

class EntryController extends Controller
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly Console $console,
    ) {}

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

    public function handle_index(): Response
    {
        $path = \dirname(__FILE__, 3) . '/data/current.json';

        if (!\file_exists($path)) {
            $json = (new JsonEncoder())->serialize(new WorkTimeDto((new DateTime())->format('Y-m-d H:i:s')));
            \file_put_contents($path, $json);

            \var_dump($json);
            return new Response();
        }

        $json = \file_get_contents($path);
        \assert(\is_string($json));

        $dto = (new JsonEncoder())->deserialize($json, WorkTimeDto::class);
        $dto = $dto->till((new DateTime())->format('Y-m-d H:i:s'));

        $work = new EntryDto(
            new DateDto((new DateTime())->format('Y-m-d')),
            $dto
        );

        \var_dump($work);

        $this->entryRepository->add($work);
        \unlink($path);

        return new Response();
    }
}
