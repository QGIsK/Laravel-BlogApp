<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post)
    {
        return CommentResource::collection(Comment::with('user')->where('post', $post)->get());
        // Resource::collection(Post::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($post, Request $request)
    {
        if (!$request->body || !$post) {
            return response()->json(['error' => 'Please provide all fiels'], 422);
        }

        $comment = Comment::create([
            'body' => $request->body,
            'user' => $request->user()->id,
            'post' => $post
        ]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        if (!$request->body) {
            return response()->json(['error' => 'Please provide all fiels'], 422);
        }


        $comment->update($request->only('body'));
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(null, 204);
    }
}
