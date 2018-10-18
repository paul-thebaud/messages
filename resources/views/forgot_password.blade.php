@extends('layout')

@push('style')
    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('title')
    Forgot Password
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card align-middle shadow">
                    <div class="card-body">
                        <h1>Forgot your password?</h1>
                        <p>
                            Just fill the form with your email address, we will send you a reset link.
                        </p>
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <form action="{{ url()->route('password.forgot') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid':'' }}"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="john.doe@example.com" required>
                                @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Send me a reset link</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
