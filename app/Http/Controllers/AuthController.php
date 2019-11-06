<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
        // $newUser = new User;
        // $newUser->name = $request->name;
        // $newUser->email = $request->email;
        // $newUser->password = bcrypt($request->password);
        // $newUser->save();

        // $credentials = $request->only('email', 'password');
        // $token = $this->guard()->attempt($credentials);
        // $user = $this->guard()->user();
        // return response()->json(['status' => 'success', 'token' => $token, 'user' => [$user]], 200)->header('Authorization', $token);


    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // dd($token);
        return $this->respondWithToken($token);
        // $credentials = $request->only('email', 'password');
        // if ($token = $this->guard()->attempt($credentials)) {
        //     $user = $this->guard()->user();
        //     return response()->json(['status' => 'success', 'token' => $token, 'user' => [$user]], 200)->header('Authorization', $token);
        // }
        // return response()->json(['error' => 'Invalid Credentials'], 401);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    private function guard()
    {
        return Auth::guard();
    }
}
