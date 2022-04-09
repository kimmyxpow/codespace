@extends('layouts.app')

@section('content')
    <div class="container">
        <x-navigation />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">Space: {{ $space->title }}</div>

                    <div class="card-body">
                        <div style="height: 500px" id="mapContainer"></div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach ($space->photos as $key => $photo)
                                    <button type="button" class="{{ $key === 0 ? 'active' : null }}"
                                        data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}"
                                        aria-label="Slide {{ $key + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($space->photos as $key => $photo)
                                    <div class="carousel-item {{ $key === 0 ? 'active' : null }}">
                                        <img src="{{ asset("storage/$photo->path") }}" class="d-block w-100" alt="...">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h3>{{ $space->title }}</h3>
                        <span>{{ $space->address }}</span>
                        <p>{{ $space->description }}</p>
                        <div id="summary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        window.action = "direction"
    </script>
@endpush
