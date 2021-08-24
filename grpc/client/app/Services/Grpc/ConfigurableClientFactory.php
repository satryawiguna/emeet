<?php

namespace App\Services\Grpc;

use App\Services\Grpc\Contracts\ClientFactory;
use Illuminate\Contracts\Config\Repository as Config;

class ConfigurableClientFactory implements ClientFactory
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function make(string $client)
    {
        $config = $this->config->get("grpc.services.{$client}");

        $authentication = strtoupper($config['authentication']);
        $authenticationMethod = "create{$authentication}Credentials";

        $credentials = $this->{$authenticationMethod}($config);

        $client = new $client($config['host'], [
            'credentials' => $credentials
        ]);

        return $client;
    }

    protected function createTlsCredentials(array $config)
    {
        $cert = file_get_contents($config['cert']);

        return \Grpc\ChannelCredentials::createSsl($cert);
    }

    protected function createInsecureCredentials(array $config)
    {
        return \Grpc\ChannelCredentials::createInsecure();
    }
}
