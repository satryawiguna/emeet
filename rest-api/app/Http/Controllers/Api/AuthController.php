<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function actionLogin(Request $request)
    {
        $validatedLogin = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validatedLogin->fails()) {
            $messages = $validatedLogin->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        if (!Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            return response()->json([
                'code_status' => 500,
                'messages' => 'Credential doesn\'t match'
            ], 403);
        }

        $user = Auth::user();
        $responseLogin = [
            'token' => $user->createToken('key:' . $user->id)->accessToken,
            'email' => $user->email,
            'first_name' => $user->contact->first_name ?? null,
            'last_name' => $user->contact->last_name  ?? null
        ];

        return response()->json($responseLogin);
    }

    public function actionLogout(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if (!$user)
            return response()->json([
                'code_status' => 500,
                'messages' => 'Logout not success'
            ], 500);

        $oAuthAccessTokens = $user->oAuthAccessTokens();
        $oAuthAccessTokens->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Logout success'
        ], 200);
    }
}
