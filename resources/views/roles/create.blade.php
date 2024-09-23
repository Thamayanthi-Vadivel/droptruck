<!-- resources/views/roles/create.blade.php -->

@extends('layouts.sidebar')

@section('content')
    <div class="container">
        <h2>Create New Role</h2>

        @if($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" required>
            </div>
            <button type="submit" class="btn btn-success">Create Role</button>
        </form>
    </div>
@endsection
