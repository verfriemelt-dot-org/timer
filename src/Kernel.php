<?php namespace timer;

    use verfriemelt\wrapped\_\AbstractKernel;

    class Kernel extends AbstractKernel {

        public function getProjectPath(): string
        {
            return \dirname(__DIR__);
        }
    }
