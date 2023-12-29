<?php

declare(strict_types=1);

namespace timer\tests\Unit;

use PHPUnit\Framework\TestCase;
use timer\Domain\Dto\EntryDto;
use timer\Domain\EntryType;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;

class EntryDtoTest extends TestCase
{
    public function test_work_dto(): void
    {
        $json = '{"date": {"day": "2023-11-21"},"workTime": {"from": "2023-11-21 07:14:23","till": "2023-11-21 07:57:13"},"type": "work"}';
        $dto = (new JsonEncoder())->deserialize($json, EntryDto::class);

        static::assertNotNull($dto->workTime);
        static::assertSame('2023-11-21 07:14:23', $dto->workTime->from);
        static::assertSame('2023-11-21 07:57:13', $dto->workTime->till);
        static::assertSame(EntryType::Work->value, $dto->type->value);
    }

    public function test_open_work_dto(): void
    {
        $json = '{"date": {"day": "2023-11-21"},"workTime": {"from": "2023-11-21 07:14:23","till": null},"type": "work"}';

        $dto = (new JsonEncoder())->deserialize($json, EntryDto::class);

        static::assertNotNull($dto->workTime);
        static::assertNull($dto->workTime->till);
        static::assertSame('2023-11-21 07:14:23', $dto->workTime->from);
        static::assertSame(EntryType::Work->value, $dto->type->value);
    }

    public function test_vacation__dto(): void
    {
        $json = '{"date": {"day": "2023-11-21"},"workTime": null,"type": "vacation"}';

        $dto = (new JsonEncoder())->deserialize($json, EntryDto::class);

        static::assertNull($dto->workTime);
        static::assertSame(EntryType::Vacation->value, $dto->type->value);
    }

    public function test_sick__dto(): void
    {
        $json = '{"date": {"day": "2023-11-21"},"workTime": null,"type": "sick"}';

        $dto = (new JsonEncoder())->deserialize($json, EntryDto::class);

        static::assertNull($dto->workTime);
        static::assertSame(EntryType::Sick->value, $dto->type->value);
    }
}
