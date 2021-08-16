<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class RegisterTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [

    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'email' => $user->email,
            'first_name' => $user->contact->first_name
        ];
    }
}
