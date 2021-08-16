<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use App\Transformers\RegisterTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractalistic\ArraySerializer;

class RegisterController extends Controller
{
    public function actionRegister(Request $request)
    {
        $validatedRegister = Validator::make($request->all(), [
            'role_id' => 'required|integer',
            'email' => 'required|unique:users|max:255|email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'contact.first_name' => 'required',
        ]);

        if ($validatedRegister->fails()) {
            $messages = $validatedRegister->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $user = new User([
            'role_id' => $request->input('role_id'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        $user->save();

        $user->contact()->save(new Contact([
            'first_name' => $request->input('first_name') ?? null,
            'last_name' => $request->input('last_name') ?? null,
            'state' => $request->input('state') ?? null,
            'region' => $request->input('region') ?? null,
            'city' => $request->input('city') ?? null,
            'address' => $request->input('address') ?? null,
            'post_code' => $request->input('post_code') ?? null,
            'phone_number' => $request->input('phone_number') ?? null
        ]));

        return fractal($user, new RegisterTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }
}
