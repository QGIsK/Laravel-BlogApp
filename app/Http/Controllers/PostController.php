<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(Post::with('user')->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->role == 1) {
            return response()->json(['error' => 'You don\'t have permissions to do this.'], 403);
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->image,
        ]);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if ($request->user()->role == 1) {
            return response()->json(['error' => 'You don\'t have permissions to do this.'], 403);
        }

        $post->update($request->only(['title', 'description']));

        return new PostResource($post);
    }

    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->role == 1) {
            return response()->json(['error' => 'You don\'t have permissions to do this.'], 403);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
