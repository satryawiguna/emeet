<?php

namespace App\Grpc\Contracts;

use Spiral\GRPC\Method;
use Spiral\GRPC\ContextInterface;
use Illuminate\Contracts\Foundation\Application;

interface ServiceInvoker
{
    public function invoke(
        string $interface,
        Method $method,
        ContextInterface $context,
        ?string $input
    ): string;

    public function getApplication(): Application;
}