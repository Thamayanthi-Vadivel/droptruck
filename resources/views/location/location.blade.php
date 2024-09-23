@extends('layouts.sidebar')

@section('content')
    <h1>Locations</h1>
    <a href="{{ route('locations.create') }}" class="float-end m-2 bg-primary btn text-white">Add Location</a>
    <table class="table table-bordered table-stripped">
        <thead>
            <tr>
                <th>Id</th>
                <th>District</th>
            </tr>
        </thead>
        <tbody>
            @foreach($locations as $location)
                <tr>
                    <td>{{ $location->id }}</td>
                    <td>{{ $location->district }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
