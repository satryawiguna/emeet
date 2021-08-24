<?php

namespace App\Grpc\Contracts;

interface Validator
{
    public function validate(array $data, array $rules): void;
}