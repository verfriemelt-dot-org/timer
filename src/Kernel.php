<?php

declare(strict_types=1);

namespace timer;

use Override;
use verfriemelt\wrapped\_\Kernel\AbstractKernel;

class Kernel extends AbstractKernel
{
    #[Override]
    public function getProjectPath(): string
    {
        return \dirname(__DIR__);
    }
}
