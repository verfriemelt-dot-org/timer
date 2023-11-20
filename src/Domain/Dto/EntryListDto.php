<?php namespace timer\Domain\Dto;

class EntryListDto {

    /** @var EntryDto[] */
    public array $entries;

    public function __construct(
        EntryDto ... $entries
    ) {
        $this->entries = $entries;
    }
}
