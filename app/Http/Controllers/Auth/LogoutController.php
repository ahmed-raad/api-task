<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        if(auth()->user())
        {
            $user = auth()->user();
            $user->api_token = null;
            $user->save();
            return response()->json(['msg' => 'Now, you are logged out!']);
        }
        return response()->json([
            'error' => 'There is something wrong, please try again!',
            'code' => 401
        ], status: 401);
    }
}
