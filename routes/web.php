<?php

use App\Http\Controllers\AdminCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome', [
        'title' => 'Welcome',
    ]);
});

Route::get('/', function () {
    return view('home', [
        'title' => 'Home',
        "active" => 'home'
    ]);
});

Route::get('/about', function () {
    $authors = [
        [
            "name" => "Arman Dwi Pangestu",
            "email" => "armandwi.pangestu7@gmail.com",
            "image" => "me-circle.png",
            'link' => 'https://github.com/armandwipangestu'
        ],
        [
            "name" => "Daka",
            "email" => "dakasakti.dev@gmail.com",
            "image" => "dakasakti.jpg",
            "link" => 'https://github.com/dakasakti',
        ],
    ];

    return view('about', [
        "title" => "About",
        "active" => "about",
        "authors" => $authors,
    ]);
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
// Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);

Route::get('/authors', [AuthorController::class, 'index']);
// Route::get('/authors/{author:username}', [AuthorController::class, 'show']);

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/register', [AuthController::class, 'storeRegister']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard/posts/getSlug', [DashboardPostController::class, 'getSlug'])->middleware('auth');
Route::resource('/dashboard/posts', DashboardPostController::class)->middleware('auth');

Route::resource('/dashboard/categories', AdminCategoryController::class)->except('show')->middleware('admin');
