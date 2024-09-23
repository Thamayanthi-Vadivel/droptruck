@extends('layouts.sidebar')

@section('content')
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col">
            <div class="d-flex">
                <button type="button" class="btn dash1" style="margin-left:600px">
                <a href="{{ route('pricings.index') }}" class="text-decoration-none text-dark">Back To Pricing</a>
            </button>
            </div>
        </div>



        <div class="col-lg-12 mt-5" style="background-color:#D9D9D9">
            <ul class="list-unstyled">
                <li class="row">
                    <strong class="col-sm-3">Pickup City:</strong>
                    <span class="col-sm-7">{{ $pricing->pickup_city}}</span>



                </li>
                <li class="row">
                    <strong class="col-sm-3">Drop City:</strong>
                    <span class="col-sm-7">{{ $pricing->drop_city  }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Vehicle Type</strong>
                    <span class="col-sm-7">{{ $pricing->truckType->name }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Body Type</strong>
                    <span class="col-sm-7">{{$pricing->body_type }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Rate From:</strong>
                    <span class="col-sm-7"> {{$pricing->rate_from}}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Rate To:</strong>
                    <span class="col-sm-7"> {{$pricing->rate_to}}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Remarks:</strong>
                    <span class="col-sm-7">{{ $pricing->remarks }}</span>
                </li>
            </ul>
        </div>

    </div>
    @endsection