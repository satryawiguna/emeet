<?php

namespace App\Transformers;

use App\Models\Contact;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
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
    public function transform(Contact $contact)
    {
        return [
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
            'state' => $contact->state,
            'region' => $contact->region,
            'city' => $contact->city,
            'address' => $contact->address,
            'post_code' => $contact->post_code,
            'phone_code' => $contact->phone_code,
            'phone_number' => $contact->phone_number,
        ];
    }
}
