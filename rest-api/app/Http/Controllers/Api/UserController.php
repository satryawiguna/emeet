<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use League\Fractal\Serializer\DataArraySerializer;

class UserController extends Controller
{
    public function actionUserContactMeUpdate(Request $request)
    {
        $userId = Auth::user()->id;

        if ($request->input('new_password')) {
            $validatedUser = Validator::make($request->all(), [
                'new_password' => ['confirmed', Password::min(8)]
            ]);

            if ($validatedUser->fails()) {
                $messages = $validatedUser->messages();

                return response()->json([
                    'code_status' => 500,
                    'messages' => $messages->all()
                ]);
            }
        }

        $user = User::find($userId);

        $user->password = ($request->input('new_password')) ?
            bcrypt($request->input('new_password')) :
            $user->password;

        $user->save();

        $contact = Contact::updateOrCreate([
            'user_id' => $userId
        ], [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'state' => $request->input('state'),
            'region' => $request->input('region'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'post_code' => $request->input('post_code'),
            'phone_code' => $request->input('phone_code'),
            'phone_number' => $request->input('phone_number'),
        ]);

        $contact->save();

        return fractal($user, new UserTransformer())
            ->parseIncludes(['contact'])
            ->toArray();
    }

    public function actionViewAllUserContact()
    {
        if (!Auth::user()->can('view', [User::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $users = User::orderBy('id', 'DESC')
            ->get();

        return fractal($users, new UserTransformer())
            ->parseIncludes('contact')
            ->toArray();
    }

    public function actionUserContactDestroy(int $id)
    {
        if (!Auth::user()->can('delete', [User::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        User::find($id)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'User was delete'
        ], 200);
    }

    public function actionUserContactBulkDestroy(Request $request)
    {
        if (!Auth::user()->can('delete', [User::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $userIds = (!is_array($request->input('ids'))) ?
            explode(',', $request->input('ids')) :
            $request->input('ids');

        User::whereIn('id', $userIds)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Users was delete'
        ], 200);
    }
}
