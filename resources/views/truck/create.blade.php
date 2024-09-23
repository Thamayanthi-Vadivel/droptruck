@extends('layouts.sidebar') {{-- Assuming you have a master layout --}}

@section('content')
<div class="container" style="margin-top:150px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>Create Truck Type</h2>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('trucks.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Truck Type Name:</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Add any other fields if needed -->

                    <button type="submit" class="btn btn-primary mt-2">Create Truck Type</button>
                </form>
            </div>
        </div>
    </div>
    </div>
        </div>
    </div>
@endsection
