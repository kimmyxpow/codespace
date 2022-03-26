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
                            @if ($space->user_id == auth()->user()->id)
                                <form action="{{ route('space.destroy', $space->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('are you sure?');">Delete</button>
                                    <a href="{{ route('space.edit', $space->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                                </form>
                            @endif
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
