<?php

namespace App\Grpc;

use Google\Protobuf\Any;
use Spiral\RoadRunner\Worker;
use Spiral\GRPC\Context;
use Spiral\GRPC\StatusCode;
use Spiral\GRPC\Exception\GRPCException;
use Spiral\GRPC\Exception\NotFoundException;
use App\Grpc\Contracts\Kernel as KernelContract;
use App\Grpc\ReflectionServiceWrapper;
use App\Grpc\Contracts\ServiceInvoker;
use Illuminate\Contracts\Foundation\Application;

class Kernel implements KernelContract
{
    protected $app;

    protected $invoker;

    protected $services = [];

    protected $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\SetRequestForConsole::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    public function __construct(Application $app, ServiceInvoker $invoker)
    {
        $this->app = $app;
        $this->invoker = $invoker;
    }

    public function registerService(string $interface): KernelContract
    {
        $service = new ReflectionServiceWrapper($this->invoker, $interface);
        $this->services[$service->getName()] = $service;

        return $this;
    }

    public function serve(Worker $worker, callable $finalize = null): void
    {
        $this->bootstrap();

        while (true) {
            $body = $worker->receive($ctx);

            if (empty($body) && empty($ctx)) {
                return;
            }

            try {
                $ctx = json_decode($ctx, true);
                $resp = $this->invoke(
                    $ctx['service'],
                    $ctx['method'],
                    $ctx['context'] ?? [],
                    $body
                );

                $worker->send($resp);
            } catch (GRPCException $e) {
                $worker->error($this->packError($e));
            } catch (\Throwable $e) {
                $worker->error((string)$e);
            } finally {
                if ($finalize !== null) {
                    call_user_func($finalize, $e ?? null);
                }
            }
        }
    }

    public function bootstrap(): void
    {
        if (! $this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }

    public function getApplication(): Application
    {
        return $this->app;
    }

    protected function invoke(
        string $service,
        string $method,
        array $context,
        ?string $body
    ): string {
        if (!isset($this->services[$service])) {
            throw new NotFoundException("Service `{$service}` not found.", StatusCode::NOT_FOUND);
        }

        return $this->services[$service]->invoke($method, new Context($context ?? []), $body);
    }

    protected function bootstrappers()
    {
        return $this->bootstrappers;
    }

    protected function packError(GRPCException $e): string
    {
        $data = [$e->getCode(), $e->getMessage()];

        foreach ($e->getDetails() as $detail) {
            $anyMessage = new Any();

            $anyMessage->pack($detail);

            $data[] = $anyMessage->serializeToString();
        }
        
        return implode("|:|", $data);
    }
}