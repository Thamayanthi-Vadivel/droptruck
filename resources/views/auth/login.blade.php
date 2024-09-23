@extends('layouts.layouts')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Login</h2>
                    <form method="POST" action="{{ route('login') }}" class="bg-white">
                        @csrf

                        @if ($errors->has('email'))
    <div class="alert alert-danger">
        {{ $errors->first('email') }}
    </div>
@endif

<!-- ... Other HTML elements ... -->

<div class="mb-3">
    <label class="form-label" for="inputEmail">Email:</label>
    <input 
        type="text" 
        name="email" 
        id="inputEmail"
        class="form-control @error('email') is-invalid @enderror">

    @error('email')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

@if ($errors->has('password'))
    <div class="alert alert-danger">
        {{ $errors->first('password') }}
    </div>
@endif

<!-- ... Other HTML elements ... -->

<div class="mb-3">
    <label class="form-label" for="inputPassword">Password:</label>
    <input 
        type="password" 
        name="password" 
        id="inputPassword"
        class="form-control @error('password') is-invalid @enderror" 
        placeholder="Password">

    @error('password')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn dash1 btn-block">Login</button>
                        </div>
                    </form>

                    <!-- Display custom error message, if any -->
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
