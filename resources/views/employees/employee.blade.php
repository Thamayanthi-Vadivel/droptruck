@extends('layouts.sidebar')
@section('content')
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col"> </div>
        <div class="col-auto">
            <button type="button" class="btn dash1 mt-3">
                    <a href="{{ route('employees.create') }}" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#exampleModal"> Add Employee</a>
                </button>
                @include('employees.create')
            </div>
        </div>
    </div>
    <div>
    @include('employees.index')
    </div>
@endsection

