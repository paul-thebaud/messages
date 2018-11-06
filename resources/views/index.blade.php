@extends('layout')

@section('title')
    Application
@endsection

@section('content')
    <div id="app" class="h-100"></div>
@endsection

@push('scripts')
    <!-- Javascript -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endpush
