<?php

namespace App\Grpc\Contracts;

use Spiral\RoadRunner\Worker;
use Illuminate\Contracts\Foundation\Application;

interface Kernel
{
    public function bootstrap(): void;

    public function registerService(string $interface): Kernel;

    public function serve(Worker $worker, callable $finalize = null): void;

    public function getApplication(): Application;
}