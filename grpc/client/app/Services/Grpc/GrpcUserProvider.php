<?php

namespace App\Services\Grpc;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Services\Grpc\Contracts\ErrorHandler;
use App\Services\Grpc\Contracts\ClientFactory;
use Protobuf\Identity\LoginRequest;
use Protobuf\Identity\UserByEmailRequest;
use Protobuf\Identity\UserByIdRequest;
use Protobuf\Identity\UserResponse;

class GrpcUserProvider implements UserProvider
{
    protected $errorHandler;

    protected $authServiceClient;

    public function __construct(ClientFactory $grpcClientFactory, ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;

        $this->authServiceClient = $grpcClientFactory->make(\Protobuf\Identity\AuthServiceClient::class);
    }

    public function retrieveById($identifier)
    {
        $request = new UserByIdRequest;

        $request->setId($identifier);
        [$response, $status] = $this->authServiceClient->UserById($request)->wait();

        $this->errorHandler->handle($status, 3);

        return $this->generateAuthenticable($response);
    }

    public function retrieveByCredentials(array $credentials)
    {
        $request = new LoginRequest();

        $request->setEmail($credentials['email']);
        $request->setPassword($credentials['password']);

        [$response, $status] = $this->authServiceClient->Login($request)->wait();

        $this->errorHandler->handle($status, 3);

        return $this->generateAuthenticable($response);
    }

    public function retrieveByEmail(string $email)
    {
        $request = new UserByEmailRequest();

        $request->setEmail($email);

        [$response, $status] = $this->authServiceClient->UserByEmail($request)->wait();

        $this->errorHandler->handle($status, 3);

        return $this->generateAuthenticable($response);
    }

    protected function generateAuthenticable(UserResponse $userResponse)
    {
        $user = new User;

        $user->id = $userResponse->getId();
        $user->email = $userResponse->getEmail();
        $user->name = $userResponse->getName();

        return $user;
    }
}
