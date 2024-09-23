@extends('layouts.sidebar')<!-- Assuming you have a layout file -->

    @section('content')

        <a href="{{ route('export-to-excel') }}" class="btn btn-sm float-end btn-success">Master Report</a>
    @endsection