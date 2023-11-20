<?php namespace timer\Domain\Dto;

    class PublicHolidayListDto {

        /** @var PublicHoliday[] */
        public array $holidays;

        public function __construct(
            PublicHoliday ... $holidays
        ) {
            $this->holidays = $holidays;
        }
    }
