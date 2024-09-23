@extends('layouts.sidebar')
@section('content')
<div class="d-flex justify-content-end gap-2">
    <div>
        <form class="mt-3" method="GET" action="{{ url('/search/truck-type') }}">
            <input class="form-control" name="query" type="search" placeholder="search...">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
    </div>
    <div>
        <button type="button" class="btn dash1 mt-3">
            <a href="{{ route('trucks.truck-type-create') }}" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#customerModal"> Add Truck Type</a>
        </button>
        @include('truck.truck-type-create')
    </div>
</div>
<div class="mb-4 mt-1">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Truck Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach($truckType as $types)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $types->name }}</td>
                    <!-- Add more columns as needed -->
                    <td class="d-flex">
                        <div>@include('truck.truck-type-edit')</div>
                        <div>@include('truck.truck-type-delete')</div>
                    </td>
                    <!-- <td>
                        <a href="{{ route('vehicles.show', $types->id) }}">
                            <i class="fa fa-eye" style="font-size:17px"></i>
                        </a>
                    </td> -->
                </tr>
                
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $truckType->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
    </div>
</div>
@endsection