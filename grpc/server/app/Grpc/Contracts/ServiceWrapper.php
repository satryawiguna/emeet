<?php

namespace App\Grpc\Contracts;

use Spiral\GRPC\ContextInterface;

interface ServiceWrapper
{
    public function getName(): string;

    public function getMethods(): array;

    public function invoke(string $method, ContextInterface $context, ?string $input): string;
}