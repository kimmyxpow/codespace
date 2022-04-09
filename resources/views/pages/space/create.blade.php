@extends('layouts.app')

@section('content')
    <div class="container">
        <x-navigation />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Space') }}</div>

                    <div class="card-body">
                        {!! Form::open(['route' => 'space.store', 'method' => 'post', 'files' => true]) !!}
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            {!! Form::text('title', null, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control']) !!}
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Adress</label>
                            {!! Form::textarea('address', null, [
    'class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control',
    'rows' => '3',
]) !!}
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            {!! Form::textarea('description', null, [
    'class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control',
    'rows' => '3',
]) !!}
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="here-maps">
                            <label for="location">Pin location</label>
                            <div style="height: 500px" id="mapContainer"></div>
                        </div>
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            {!! Form::text('latitude', null, ['class' => $errors->has('latitude') ? 'form-control is-invalid' : 'form-control', 'id' => 'lat']) !!}
                            @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            {!! Form::text('longitude', null, ['class' => $errors->has('longitude') ? 'form-control is-invalid' : 'form-control', 'id' => 'lng']) !!}
                            @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="increment">
                            <label for="photo">Photo</label>
                            <div class="input-group mb-2">
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo[]"
                                    id="photo">
                                <button class="btn btn-outline-success btn-add" type="button">
                                    <i class="fas fa-plus-square"></i>
                                </button>
                                @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="clone invisible position-absolute">
                            <div class="input-group mb-2">
                                <input type="file" class="form-control" name="photo[]" id="photo">
                                <button class="btn btn-outline-danger btn-remove" type="button">
                                    <i class="fas fa-minus-square"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Submit</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        window.action = "submit";
        $(document).ready(function() {
            $(".btn-add").click(function() {
                let markup = $(".invisible").html();
                $(".increment").append(markup);
            });
            $("body").on("click", ".btn-remove", function() {
                $(this).parents(".input-group").remove();
            })
        })
    </script>
@endpush
