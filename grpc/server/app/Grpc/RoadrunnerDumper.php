<?php

namespace App\Grpc;

use Spiral\Debug;
use App\Grpc\Contracts\Dumper;

class RoadrunnerDumper implements Dumper
{
    protected $dumper;

    public function __construct()
    {
        $this->dumper = new Debug\Dumper();
        $this->dumper->setRenderer(Debug\Dumper::ERROR_LOG, new Debug\Renderer\ConsoleRenderer());
    }

    public function dump($value)
    {
        $this->dumper->dump($value, Debug\Dumper::ERROR_LOG);
    }
}