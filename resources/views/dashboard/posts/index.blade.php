@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Posts</h1>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success col-lg-8" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive col-lg-8">
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create new Post</a>

        @if(count($posts))
            <table class="table table-striped table-sm" aria-label="list post">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->category->name }}</td>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" class="badge bg-info">
                                    <span data-feather="eye"></span>
                                </a>
                                <a href="{{ route('posts.edit', $post) }}" class="badge bg-warning">
                                    <span data-feather="edit"></span>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                    @method("DELETE")
                                    @csrf
                                    <button class="badge bg-danger border-0" onclick="return confirm('Are you sure?')">
                                        <span data-feather="x-circle"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No posts available.</p>
        @endif
    </div>
@endsection
