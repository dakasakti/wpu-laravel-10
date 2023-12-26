@extends('layouts.main')

@section('container')
    <h1 class="mb-5">Authors</h1>

    @foreach ($authors as $author)
        <ul>
            <li>
                <h2>
                    <a href="/authors/{{ $author->id }}" class="text-decoration-none">{{ $author->name }}</a>
                </h2>
            </li>
        </ul>
    @endforeach
@endsection