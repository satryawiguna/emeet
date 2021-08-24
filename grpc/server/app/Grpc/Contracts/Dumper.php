<?php

namespace App\Grpc\Contracts;

interface Dumper
{
    public function dump($value);
}