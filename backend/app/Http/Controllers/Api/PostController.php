<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of news posts.
     */
    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->query('admin') !== 'true') {
            $query->where('status', 'published');
        }

        if ($request->has('category') && $request->category !== 'All News') {
            $query->where('category', $request->category);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created post (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'content_en' => 'required|string',
            'category' => 'required|in:news,press_release,update',
            'title_am' => 'nullable|string',
            'title_or' => 'nullable|string',
            'content_am' => 'nullable|string',
            'content_or' => 'nullable|string',
            'status' => 'nullable|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        $data = $validated;
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('thumbnail')->getClientOriginalExtension());
            $data['thumbnail_url'] = '/uploads/' . basename($path);
        }

        if (($data['status'] ?? 'draft') === 'published' && !isset($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);

        return response()->json(['message' => 'Post created successfully', 'id' => $post->id], 201);
    }

    /**
     * Display the specified post.
     */
    public function show(string $id)
    {
        return Post::findOrFail($id);
    }

    /**
     * Update the specified post (admin).
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('thumbnail')->getClientOriginalExtension());
            $data['thumbnail_url'] = '/uploads/' . basename($path);
        }

        if (($data['status'] ?? $post->status) === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return response()->json(['message' => 'Post updated successfully']);
    }

    /**
     * Remove the specified post (admin).
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
