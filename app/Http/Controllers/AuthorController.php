<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        return view('authors', [
            'title' => 'Authors',
            "active" => "authors",
            'authors' => User::latest()->get()
        ]);
    }

    public function show(User $author)
    {
        return view('posts', [
            'title' => "Post By Author: $author->name",
            "active" => "authors",
            'posts' => $author->posts->load('category', 'author'),
        ]);
    }
}
