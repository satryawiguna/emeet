<?php

namespace App\Services\Grpc\Contracts;

interface ErrorHandler
{
    public function handle($status, $codeToSend = null);
}
