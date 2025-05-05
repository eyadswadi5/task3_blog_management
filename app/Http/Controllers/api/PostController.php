<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\posts\StorePostRequest;
use App\Http\Requests\api\posts\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        try {
            $posts = Post::all();
            return $this->success(["posts" => $posts]);
        } catch (QueryException $e) {
            return $this->failed("unable to get posts from the database", 500, [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request) : JsonResponse
    {
        try {
            $post = Post::create($request->validated());
            return $this->success(["post" => $post], "post created successfully!", 201);
        } catch (QueryException $e) {
            return $this->failed("post not created!", 500, [$e->getMessage()]);
        }   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : JsonResponse
    {
        try {
            $post = Post::findOrFail($id);
            return $this->success(["post" => $post]);
        } catch (QueryException $e) {
            return $this->failed("unable to get post from database", 500, [$e->getMessage()]);
        } catch (ModelNotFoundException $e) {
            return $this->failed("post not found", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id) : JsonResponse
    {
        try {
            $post = Post::findOrFail($id);
            $post->update($request->validated());
            return $this->success(["post" => $post], "post updated successfully", 200);
        } catch (QueryException $e) {
            return $this->failed("unable to update post", 500, [$e->getMessage()]);
        } catch (ModelNotFoundException $e) {
            return $this->failed("post not found", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
            return $this->success(["post" => $post], "post deleted successfullt");
        } catch (QueryException $e) {
            return $this->failed("unable to delete post", 500, [$e->getMessage()]);
        } catch (ModelNotFoundException $e) {
            return $this->failed("post not found", 404);
        }
    }
}
