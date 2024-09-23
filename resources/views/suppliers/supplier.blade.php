@extends('layouts.sidebar')

@section('content')
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col"> </div>
        <div class="col-auto">
            <button type="button" class="btn dash1 mt-3">
                <a href="{{ route('suppliers.create') }}" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#customerModal"> Create Supplieres</a>
            </button>
            @include('suppliers.create') 
        </div>
    </div>
</div>
<div>
@include('suppliers.index')
</div>
@endsection