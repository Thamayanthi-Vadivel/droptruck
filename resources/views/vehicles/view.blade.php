@extends('layouts.sidebar')

@section('content')
<div class="main mb-4 mt-1">
<div class="row align-items-center">
    <div class="col">
        <div class="d-flex justify-content-start">
            {{-- <div class="me-3">@include('vehicles.edit')</div>
            <span>@include('vehicles.delete')</span> --}}
            <button type="button" class="btn dash1" style="margin-left:600px">
            <a href="{{ route('vehicles.index') }}" class="text-decoration-none text-white float-end">Back To Truck</a>
        </button>
        </div>
    </div>
</div>



            <div class="col-lg-12 mt-5" style="background-color:#D9D9D9">
                <ul class="list-unstyled">
                    <li class="row">
                        <strong class="col-sm-3">Vehicle Number:</strong>
                        <span class="col-sm-7">{{ $vehicle->vehicle_number  }}</span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">Vehicle Type</strong>
                        <span class="col-sm-7">{{ ($vehicle->truckType) ? $vehicle->truckType->name : 'N/A' }}</span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">Body Type</strong>
                        <span class="col-sm-7">{{ $vehicle->body_type }}</span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">Tonnage Passing</strong>
                        <span class="col-sm-7">{{ $vehicle->tonnage_passing  }}</span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">Driver Number</strong>
                        <span class="col-sm-7">{{ $vehicle->driver_number }}</span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">Supplier Name:</strong>
                        <span class="col-sm-7">{{ $vehicle->supplier ? $vehicle->supplier->supplier_name : 'No Supplier Assigned' }}</span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">Status</strong>
                        <span class="col-sm-7">{{ ($vehicle->status == 1) ? 'Active' : 'Inactive' }}</span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">RC Book:</strong>
                        <span class="col-sm-7">
                            @if( $vehicle->rc_book )
                            <a href="{{ asset($vehicle->rc_book) }}" target="_blank" class="text-decoration-underline">RCbook</a>
                            @else
                            No PAN Card uploaded
                            @endif
                        </span>
                    </li>
                    <li class="row">
                        <strong class="col-sm-3">Driving License:</strong>
                        <span class="col-sm-7">
                            @if( $vehicle->driving_license)
                            <a href="{{ asset($vehicle->driving_license) }}" target="_blank" class="text-decoration-underline">Driving License</a>
                            @else
                            No Business Card uploaded
                            @endif
                        </span>
                    </li>
                    <li class="row">
                    <strong class="col-sm-3">Remarks:</strong>
                    <span class="col-sm-7">{{ $vehicle->remarks }}</span>
                </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection