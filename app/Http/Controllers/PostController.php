<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $posts = auth()->user()->posts;

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found '
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $post->toArray()
        ], 400);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;

        if (auth()->user()->posts()->save($post))
            return response()->json([
                'success' => true,
                'data' => $post->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Post not added'
            ], 500);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        $updated = $post->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Post can not be updated'
            ], 500);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        if ($post->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }
}
