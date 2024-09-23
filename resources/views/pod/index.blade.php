<!-- resources/views/pod/index.blade.php -->

@extends('layouts.sidebar')

@section('content')
<style>
    .btn-warning.custom-active {
        background: linear-gradient(135deg, #007bff, #8a2be2);
        color: #fff;
        border: #8a2be2;
    }
    .bg-gradient-info {
            background-image: radial-gradient(515px at 48.7% 52.8%, rgb(239, 110, 110) 0%, rgb(230, 25, 25) 46.5%, rgb(154, 11, 11) 100.2%);
        }
        .circle-badge {
        border-radius: 50%;
    }
</style>
<div>
            <h2 class="btn btn-primary text-white fw-bolder float-end mt-1">User : {{ auth()->user()->name }}</h2>
        </div>
<div class="mt-3">
    <div class="m-3">
        <a class="btn btn-warning" href="{{route('confirmed-trips')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Waiting for driver
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $confirmedTripsCount }}
    </span>
        </a>
        <a class="btn btn-warning" href="{{route('trips.index')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Loading
        <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $tripsCount }}
    </span>
    </a>
        <a class="btn btn-warning" href="{{route('loading')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">On The Road
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $loadingCount }}
        </span>
        </a>
        <a class="btn btn-warning" href="{{route('unloading')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Unloading
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $unloading }}
    </span>
        </a>
        <a class="btn btn-warning" href="{{route('extra_costs.index')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Pod
        <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $pod }}
    </span>
    </a>
        <a class="btn btn-warning custom-active" href="{{route('pods.index')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Complete Trips
        <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ (auth()->user()->role_id === 1 || auth()->user()->role_id === 2 || auth()->user()->role_id === 3) ? $podsCount->count() : $pods->count() }}
    </span></a>
    </div>
</div>
<div class="container">
<table class="table table-bordered table-striped table-hover" style="font-size:8px;">
        <thead>
            <tr>
                <th class="bg-gradient-info text-light">Indent ID</th>
                <!--<th class="bg-gradient-info text-light">Courier Receipt No</th>-->
                @if(auth()->user()->role_id != 4)
                    <th class="bg-gradient-info text-light">Customer Name</th>
                    <th class="bg-gradient-info text-light">Company Name</th>
                    <th class="bg-gradient-info text-light">Number 1</th>
                @endif
                <th class="bg-gradient-info text-light">Pickup Location</th>
                <th class="bg-gradient-info text-light">Drop Location</th>
                <th class="bg-gradient-info text-light">Material Type</th>
                <th class="bg-gradient-info text-light">Truck Type</th>
                <th class="bg-gradient-info text-light">Body type</th>
                <th class="bg-gradient-info text-light">Weight</th>
                <th class="bg-gradient-info text-light">Driver Rate</th>
                @if(auth()->user()->role_id != 4)
                <th class="bg-gradient-info text-light">Customer Rate</th>
                @endif
                <th class="bg-gradient-info text-light">Remarks</th>
                <th class="bg-gradient-info text-light">Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pods as $pod)
            <tr>
                <td>
            <a href="{{ route('completed-trips.details', ['id' => $pod->indent->id]) }}" class="text-primary">
            {{ $pod->indent->getUniqueENQNumber() }}
        </a>
                </td>
                {{-- <td>{{ $pod->courier_receipt_no ? $pod->courier_receipt_no : 'N/A' }}</td>--}}
                @if(auth()->user()->role_id != 4)
                    <td>{{ $pod->indent->customer_name }}</td>
                    <td>{{ $pod->indent->company_name }}</td>
                    <td>{{ $pod->indent->number_1 }}</td>
                @endif
                <td>{{ $pod->indent->pickup_location_id ? $pod->indent->pickup_location_id : 'N/A' }}</td>
                <td>{{ $pod->indent->drop_location_id ? $pod->indent->drop_location_id : 'N/A' }}</td>
                <td>{{ ($pod->indent->materialType) ? $pod->indent->materialType->name : 'N/A' }}</td>
                <td>{{ $pod->indent->truckType->name }}</td>
                <td>{{ $pod->indent->body_type }}</td>
                <td>{{ $pod->indent->weight }} {{ $pod->indent->weight_unit }}</td>
                <td>{{ $pod->indent->driver_rate }}</td> 
                @if(auth()->user()->role_id != 4)
                <td>{{ $pod->indent->customer_rate }}</td>
                @endif
                <td>{{ $pod->indent->remarks }}</td>
                <td>{{ $pod->indent->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if(auth()->user()->role_id === 1 || auth()->user()->role_id === 2 || auth()->user()->role_id === 3)
        <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $pods->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
        </div>
    @endif
</div>
@endsection
<script>
    setTimeout(function(){
       location.reload();
    }, 60000); // 10000 milliseconds = 10 seconds
</script>