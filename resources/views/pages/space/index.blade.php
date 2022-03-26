@extends('layouts.app')

@section('content')
<div class="container">
    <x-space />
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @foreach ($spaces as $space)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title">{{ $space->title }}</h5>
                            <form action="#">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                <a href="#" class="btn btn-sm btn-info">Edit</a>
                            </form>
                        </div>
                        <h6 class="card-subtitle">{{ $space->address }}</h6>
                        <p class="card-text">{{ $space->description }}</p>
                        <a href="#" class="card-link">Direction</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row justify-content-center">
        {{ $spaces->links() }}
    </div>
</div>
@endsection
