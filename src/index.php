<?php

declare(strict_types=1);

namespace timer;

use verfriemelt\wrapped\_\Cli\Console;
use verfriemelt\wrapped\_\DotEnv\DotEnv;

define('_', true);

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Berlin');

(new DotEnv())->load('.env');

exit((new Kernel())->boot()->execute(new Console())->value);
