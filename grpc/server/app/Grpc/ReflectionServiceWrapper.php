<?php

namespace App\Grpc;

use App\Grpc\Contracts\ServiceInvoker;
use App\Grpc\Contracts\ServiceWrapper;
use Spiral\GRPC\Method;
use Spiral\GRPC\ContextInterface;
use Spiral\GRPC\Exception\ServiceException;
use Spiral\GRPC\Exception\NotFoundException;

class ReflectionServiceWrapper implements ServiceWrapper
{
    protected $name;

    protected $methods = [];

    protected $invoker;

    protected $interface;

    public function __construct(
        ServiceInvoker $invoker,
        string $interface
    ) { 
        $this->invoker = $invoker;
        $this->interface = $interface;

        $this->configure($interface);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function invoke(string $method, ContextInterface $context, ?string $input): string
    {
        if (!isset($this->methods[$method])) {
            throw new NotFoundException("Method `{$method}` not found in service `{$this->name}`.");
        }

        return $this->invoker->invoke($this->interface, $this->methods[$method], $context, $input);
    }

    protected function configure(string $interface)
    {
        try {
            $r = new \ReflectionClass($interface);
            if (!$r->hasConstant('NAME')) {
                throw new ServiceException(
                    "Invalid service interface `{$interface}`, constant `NAME` not found."
                );
            }
            $this->name = $r->getConstant('NAME');
        } catch (\ReflectionException $e) {
            throw new ServiceException(
                "Invalid service interface `{$interface}`.",
                StatusCode::INTERNAL,
                $e
            );
        }

        $this->methods = $this->fetchMethods($interface);
    }

    protected function fetchMethods(string $interface): array
    {
        $reflection = new \ReflectionClass($interface);

        $methods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (Method::match($method)) {
                $methods[$method->getName()] = Method::parse($method);
            }
        }

        return $methods;
    }
}
