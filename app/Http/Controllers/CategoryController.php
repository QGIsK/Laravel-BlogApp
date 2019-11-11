<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->tag) {
            return response()->json(['error' => 'Please provide all fields']);
        }
        
        $tag = Category::create([
              'tag' => $request->tag
          ]);

        return new CategoryResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $tag)
    {
        if (!$request->tag) {
            return response()->json(['error' => 'Please provide all fields']);
        }

        $tag->update($request->only(['tag']));

        return new CategoryResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $tag)
    {
        $tag->delete();

        return response()->json(null, 204);
    }
}
