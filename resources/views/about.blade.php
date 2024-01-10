@extends('layouts.main')

@section('container')
    <h1 class="mb-3 text-center">Halaman About</h1>

    <div class="row justify-content-center">
        @foreach($authors as $author)
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card mx-auto" style="width: 18rem;">
                    <img src="{{ asset('assets/img/' . $author['image']) }}" alt="{{ $author['name'] }}" class="img-thumbnail rounded-circle mx-auto d-block img-fluid" style="max-width: 200px; max-height: 300px;">

                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $author['name'] }}</h5>
                        <p class="card-text">{{ $author['email'] }}</p>
                        <a href="{{ $author['link'] }}" target="__blank" class="btn btn-primary">Github</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
