<?php

namespace App\Services\Grpc\Contracts;

interface ClientFactory
{
    public function make(string $client);
}
