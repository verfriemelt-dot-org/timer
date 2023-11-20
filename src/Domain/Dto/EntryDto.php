<?php namespace timer\Domain\Dto;

    use function assert;
    use function in_array;

    class EntryDto {
        public function __construct(
            public DateDto $date,
            public ?WorkTimeDto $workTime = null,
            public string $type = 'work',
        ) {
            assert(in_array($this->type, ['vacation', 'work', 'sick']));

        }
    }
