<?php

declare(strict_types=1);

namespace timer;

use verfriemelt\wrapped\_\AbstractKernel;
use Override;

class Kernel extends AbstractKernel
{
    #[Override]
    public function getProjectPath(): string
    {
        return \dirname(__DIR__);
    }
}
