<?php

namespace App\Grpc\Services\Identity;

use App\User;
use Spiral\GRPC;
use Protobuf\Identity;
use App\Jobs\RegisterUser;
use App\Grpc\Contracts\Validator;
use Illuminate\Contracts\Hashing\Hasher;
use Spiral\GRPC\Exception\InvokeException;
use Spiral\GRPC\StatusCode;

class AuthService implements Identity\AuthServiceInterface
{
    protected $validator;

    protected $hasher;

    public function __construct(Validator $validator, Hasher $hasher)
    {
        $this->validator = $validator;
        $this->hasher = $hasher;
    }

    public function Register(GRPC\ContextInterface $ctx, Identity\RegisterRequest $in): Identity\RegisterResponse
    {
        $response = new Identity\RegisterResponse;
        $arrayInput = json_decode($in->serializeToJsonString(), true);

        $this->validator->validate($arrayInput, [
            'email' => 'bail|required|email|unique:users,email',
            'name' => 'required|max:255',
            'password' => 'required|confirmed',
        ]);

        RegisterUser::dispatchNow($arrayInput);

        return $response;
    }

    public function Login(GRPC\ContextInterface $ctx, Identity\LoginRequest $in): Identity\UserResponse
    {
        $arrayInput = json_decode($in->serializeToJsonString(), true);

        $this->validator->validate($arrayInput, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $arrayInput['email'])->first();

        if(!$this->hasher->check($arrayInput['password'], $user->password)) {
            throw new InvokeException("These credentials do not match our records.", StatusCode::INVALID_ARGUMENT);
        }

        $response = $this->prepareUserResponse($user);

        return $response;
    }

    public function UserByEmail(GRPC\ContextInterface $ctx, Identity\UserByEmailRequest $in): Identity\UserResponse
    {
        $arrayInput = json_decode($in->serializeToJsonString(), true);

        $this->validator->validate($arrayInput, [
            'email' => 'required|email'
        ]);

        $user = User::where('email', $arrayInput['email'])->first();
        $response = $this->prepareUserResponse($user);

        return $response;
    }

    public function UserById(GRPC\ContextInterface $ctx, Identity\UserByIdRequest $in): Identity\UserResponse
    {
        $user = User::find($in->getId());
        $response = $this->prepareUserResponse($user);

        return $response;
    }

    protected function prepareUserResponse(User $user = null): Identity\UserResponse
    {
        if(!$user) {
            throw new InvokeException("These credentials do not match our records.", StatusCode::INVALID_ARGUMENT);
        }

        $response = new Identity\UserResponse;

        $response->setId($user->id);
        $response->setEmail($user->email);
        $response->setName($user->name);

        return $response;
    }
}
