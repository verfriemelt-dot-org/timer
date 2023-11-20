<?php
namespace timer\Domain\Repository;

use DateTime;
use timer\Domain\Dto\PublicHoliday;
use timer\Domain\Dto\PublicHolidayListDto;
use verfriemelt\wrapped\_\Serializer\Encoder\JsonEncoder;

use function dirname;
use function file_exists;
use function file_get_contents;
use function file_put_contents;

class HolidayRepository extends AbstractRepository
{
    private string $path;

    public function __construct() {
        $this->path = dirname(__FILE__, 4) . "/data/holidays.json";

    }

    public function all(): PublicHolidayListDto {


        if (!file_exists($this->path)) {
            $json = '[]';
        } else {
            $json = file_get_contents($this->path) ?: throw new \RuntimeException("cant read {$this->path}");
        }

        return (new JsonEncoder())->deserialize($json, PublicHolidayListDto::class);
    }

    public function truncate(): void {
        file_put_contents($this->path, '[]');
    }

    public function add(PublicHoliday $publicHoliday)
    {
        $newList = new PublicHolidayListDto(

            $publicHoliday,
            ... array_values($this->all()->holidays),
        );

        $this->write($newList);
    }

    public function write(PublicHolidayListDto $dto) {
        file_put_contents($this->path, (new JsonEncoder())->serialize($dto->holidays, true));
    }

    public function isHoliday(DateTime $day): bool {

        $holidays = array_map(fn(PublicHoliday $holiday): string => $holiday->date->day, $this->all()->holidays);

        return in_array($day->format('Y-m-d'), $holidays);
    }
}
