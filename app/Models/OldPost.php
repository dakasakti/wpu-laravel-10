<?php

namespace App\Models;

class OldPost
{
    private static $blogPosts = [
        [
            'title' => 'Judul Post Pertama',
            'slug' => 'judul-post-pertama',
            'author' => 'Arman Dwi Pangestu',
            'body' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi aliquid perspiciatis possimus. Quae sapiente molestiae perferendis ut dolorum illum fugit corrupti! Ratione pariatur quis odio! Explicabo quo incidunt velit aliquam iure, doloribus natus molestiae ab officiis, ea distinctio fugit dolores eos quam magni aliquid libero nihil ex iusto unde? Voluptates!'
        ],
        [
            'title' => 'Judul Post Kedua',
            'slug' => 'judul-post-kedua',
            'author' => 'Sandhika Galih',
            'body' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Delectus, nobis qui. Officia eius, debitis eveniet nisi culpa quas, voluptate enim impedit ipsa corrupti sapiente nesciunt mollitia facilis at cumque laudantium.'
        ]
    ];

    public static function all()
    {
        return collect(self::$blogPosts);
    }

    public static function find($slug)
    {
        $posts = static::all();
        return $posts->firstWhere('slug', $slug);
    }
}
