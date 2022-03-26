@extends('layouts.app')

@section('content')
<div class="container">
    <x-navigation />
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Submit My Space</div>

                <div class="card-body">
                   {!! Form::model($space, ['route' => ['space.update', $space->id], 'method' => 'put', 'files' => true]) !!}
                    <div class="mb-3">
                        <label class="form-label" for="">Title</label>
                        {!! Form::text('title', null, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control']) !!}
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Address</label>
                        {!! Form::textarea('address', null, [
                            'class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control',
                            'cols' => "10",
                            'rows' => "3"
                        ]) !!}
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Description</label>
                        {!! Form::textarea('description', null, [
                            'class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control',
                            'cols' => "10",
                            'rows' => "3"
                        ]) !!}
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div id="here-maps">
                        <label class="form-label" for="">Pin Location</label>
                        <div style="height: 500px" id="mapContainer"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Latitude</label>
                        {!! Form::text('latitude', null, ['class' => $errors->has('latitude') ? 'form-control is-invalid' : 'form-control', 'id' => 'lat']) !!}
                        @error('latitude')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Longitude</label>
                        {!! Form::text('longitude', null, ['class' => $errors->has('longitude') ? 'form-control is-invalid' : 'form-control', 'id' => 'lng']) !!}
                        @error('longitude')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                   {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        window.action = "submit"
    </script>
@endpush