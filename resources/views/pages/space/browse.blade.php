@extends('layouts.app')

@section('content')
<div class="container">
    <x-space />
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Browse Codespace</div>

                <div class="card-body">
                    <div style="height: 500px" id="mapContainer"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        window.action = "browse"
    </script>
@endpush