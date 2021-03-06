<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function generateJWT(User $user) {
        $payload = [
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'isSeller' => $user->isSeller,
            'iat' => time(),
            'exp' => time() + 60*60
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function authenticate(User $user) {

        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->generateJWT($user)
            ], 200);
        }

        return response()->json([
            'error' => 'Email or password is incorrect.'
        ], 400);
    }
}
