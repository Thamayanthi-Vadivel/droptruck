@extends('layouts.sidebar')
@section('content')
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col">
            <a href="{{ route('employees.edit', $users->id) }}" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#updateEmployeeModal">Update</a>
            @include('employees.delete')
        </div>
        <div class="col-auto  pe-5 ps-5">
            <button type="button" class="btn dash1">
                <a href="{{ route('employees.index') }}" class="text-decoration-none text-dark"> Back To Employee</a>
            </button>
        </div>
        <div class="col-lg-12 mt-5" style="background-color:#D9D9D9">
            <ul class="list-unstyled">
                <li class="row">
                    <strong class="col-sm-3">Name:</strong>
                    <span class="col-sm-7">{{ $users->name }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Email:</strong>
                    <span class="col-sm-7">{{ $users->email }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Contact:</strong>
                    <span class="col-sm-7">{{ $users->contact }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Designation:</strong>
                    <span class="col-sm-7">{{ $users->designation }}</span>
                </li>
                <li class="row">
                        <strong class="col-sm-3">Status:</strong>
                        <span class="col-sm-7">{{ $users->status ? 'Active' : 'Inactive' }}</span>
                    </li>
                <li class="row">
                    <strong class="col-sm-3">User Type:</strong>
                    <span class="col-sm-7">
                       @if($users->role)
                            {{ $users->role->type}}
                        @else
                            'N/A'
                        @endif
                    </span>
                </li>

                <li class="row">
                    <strong class="col-sm-3">Remarks:</strong>
                    <span class="col-sm-7">{{ $users->remarks }}</span>
                </li>
            </ul>
        </div>

    </div>

    @include('employees.edit')
    @endsection