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
        return PostResource::collection(Post::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->body || !$request->title) {
            return response()->json(['error' => 'Please provide all fiels'], 422);
        }

        if (!$request->image) {
            $request->image = 'https://images.unsplash.com/photo-1572546156422-d6fb14c8a8a0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80';
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->image,
            'allowComments' => $request->allowComments
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
        if (!$request->body || !$request->title) {
            return response()->json(['error' => 'Please provide all fiels'], 422);
        }

        if (!$request->image) {
            $request->image = 'https://images.unsplash.com/photo-1572546156422-d6fb14c8a8a0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80';
        }

        $post->update($request->only(['title', 'body', 'image', 'allowComments']));
        return new PostResource($post);
    }

    public function destroy(Request $request, Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
