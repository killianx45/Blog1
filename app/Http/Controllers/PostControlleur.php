<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostControlleur extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
        $title = 'Les posts les plus récents';
        $name_user = $request->user()->name;
        return view('home')->with('posts', $posts)->with('title', $title)->with('name_user', $name_user);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'categories' => 'array|max:2',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->slug = Str::slug($post->title);
        $post->id_user = $request->user()->id;

        $post->save();
        $post->categories()->attach($request->categories);

        return redirect()->route('posts.index')->with('success', 'Post créé avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $post = Post::with('comments')->find($id);
        $name_user = $request->user()->name;
        return view('posts.show', compact('post', 'name_user'));
    }

    public function show_by_slug(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->first();
        $name_user = $request->user()->name;

        return view('posts.show')->with('post', $post)->with('name_user', $name_user);
        // return 'ok' . $slug;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
