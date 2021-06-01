<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(),
        [
            'name' => ['required', 'max:25', 'min:5'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
        ],
        [
            'name.required' => 'Please, add your name!',
            'email.required' => 'Please, add your email!',
            'email.unique' => 'This is email is used, please choose a different one!'
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }
        else {
            $new_user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(length: 60)
            ]);

            return $new_user;
        }
    }
}
