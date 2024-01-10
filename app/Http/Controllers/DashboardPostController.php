<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DashboardPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.posts.index', [
            'posts' => Post::where('user_id', auth()->id())->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.posts.create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|file|max:2048',
            'body' => 'required'
        ]);

        $file = $validateData['image'] ?? null;
        if ($file) {
            // $imagePath = $request->file('image')->store('public/post-images');
            $validateData['image'] = $this->storeFile($file, 'public/posts');
        }

        $validateData['user_id'] = auth()->id();
        $validateData['excerpt'] = Str::limit(strip_tags($request->body), 200);

        Post::create($validateData);

        return to_route('posts.index')->with('success', 'New post has been added!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string $path
     * @param  string $type
     * @return null|string
     */
    protected function storeFile(UploadedFile $file, $path = '', $type = 'public'): null|string
    {
        $filePath = Storage::putFile($path, $file, $type);
        if (!$filePath) {
            return null;
        }

        return str_replace('public/', '', $filePath);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $this->checkAccess($post);

        return view('dashboard.posts.show', [
            "post" => $post
        ]);
    }

    protected function checkAccess($data)
    {
        if ($data['user_id'] !== auth()->id()) {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $this->checkAccess($post);

        return view('dashboard.posts.edit', [
            'post' => $post,
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->checkAccess($post);

        $rules = [
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'category_id' => 'required',
            'image' => 'nullable|image|file|max:2048',
            'body' => 'required'
        ];

        $validateData = $request->validate($rules);

        $file = $validateData['image'] ?? null;
        if ($file) {
            $validateData['image'] = $this->storeFile($file, 'public/posts');
        }

        $oldImagePath = $post->image;
        if ($file && $oldImagePath) {
            Storage::delete('public/' . $oldImagePath);
        }

        // $validateData['user_id'] = auth()->user()->id;
        $validateData['excerpt'] = Str::limit(strip_tags($request->body), 200);

        // Post::where('id', $post->id)->update($validateData);
        $post->update($validateData);

        return to_route('posts.index')->with('success', 'Post has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->checkAccess($post);

        if ($post->image) {
            Storage::delete($post->image);
        }

        // Post::destroy($post->id);
        $post->delete();

        return to_route('posts.index')->with('success', 'Post has been deleted!');
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(Post::class, 'slug', $request->title);

        return response()->json([
            'slug' => $slug
        ]);
    }
}
