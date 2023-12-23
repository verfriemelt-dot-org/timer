<?php namespace tests\Commands;

use PHPUnit\Framework\TestCase;
use timer\Controller\HolidayController;
use timer\Domain\Repository\HolidayRepositoryInterface;
use timer\Repository\HolidayRepository;
use verfriemelt\wrapped\_\AbstractKernel;
use verfriemelt\wrapped\_\Kernel\KernelInterface;

class HolidayCommandTest extends TestCase
{

    private KernelInterface $kernel;

    public function setUp(): void
    {
        $this->kernel = new class() extends AbstractKernel {
            public function getProjectPath(): string
            {
                return TEST_ROOT;
            }
        };

        $this->kernel->getContainer()->register(
            HolidayRepositoryInterface::class,
            new HolidayRepository(TEST_ROOT . '/_data')
        );
    }

    public function test(): void
    {
        $controller = $this->kernel->getContainer()->get(HolidayController::class);
        $controller->handle_list();
    }
}
