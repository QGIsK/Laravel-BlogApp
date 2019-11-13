<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;

class UserController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = User::find($request->user()->id);
    //     $posts = Post::where('user', $request->user()->id)->get();
        

        
    //     return response()->json(
    //         [
    //             'user' => $user->toArray(),
    //             'posts' => $posts->toArray()
    //         ],
    //         200
    //     );
    // }

    public function update(Request $request, User $user)
    {
        if (!$request->name) {
            return response()->json(['error' => 'Please provide all fiels'], 422);
        }
        
        $user->update($request->only(['name', 'weekly_digest']));
        return new UserResource($user);
    }

    public function show(Request $request, $id)
    {
        $user = User::select('name', 'id', 'role', 'created_at')->find($id);
        $posts = Post::where('user_id', $id)->get();
        
        // dd($posts);
        return response()->json(
            [
                'user' => $user->toArray(),
                'posts' => $posts
            ],
            200
        );
    }
}
