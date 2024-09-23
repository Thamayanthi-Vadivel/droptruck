@extends('layouts.sidebar')

@section('content')
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

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
        <a class="btn btn-warning custom-active" href="{{route('extra_costs.index')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Pod
        <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $extraCosts->count() }}
    </span>
    </a>
        <a class="btn btn-warning" href="{{route('pods.index')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Complete Trips
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $completedTrips }}
    </span>
        </a>
    </div>
</div>
<div class="container">


    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <table class="table table-bordered table-striped table-hover" style="font-size:8px;">
        <thead>
            <tr>
                <th class="bg-gradient-info text-light">Enq NO</th>
                @if(auth()->user()->role_id != 4)
                    <th class="bg-gradient-info text-light">Customer Name</th>
                    <th class="bg-gradient-info text-light">Company Name</th>
                    <th class="bg-gradient-info text-light">Number 1</th>
                @endif
                <th class="bg-gradient-info text-light">Pickup Location</th>
                <th class="bg-gradient-info text-light">Drop Location</th>
                <th class="bg-gradient-info text-light">Material Type</th>
                <th class="bg-gradient-info text-light">Truck Type</th>
                <th class="bg-gradient-info text-light">Body Type</th>
                <th class="bg-gradient-info text-light">Weight</th>
                <th class="bg-gradient-info text-light">Driver Rate</th>
                @if(auth()->user()->role_id != 4)
                <th class="bg-gradient-info text-light">Customer Rate</th>
                @endif
                <th class="bg-gradient-info text-light">Remarks</th>
                <th class="bg-gradient-info text-light">Sales Person</th>
                @if(auth()->user()->role_id != 3)
                <th class="bg-gradient-info text-light">Supplier Name</th>
                @endif
                <th class="bg-gradient-info text-light">Extra Cost Type</th>
                <th class="bg-gradient-info text-light">Amount</th>
                <th class="bg-gradient-info text-light">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($extraCosts as $extraCost)
                @php
                    $confirmedRate = DB::table('rates')->where('indent_id',$extraCost->indent_id)->where('is_confirmed_rate', 1)->first();
                    if($confirmedRate) {
                        $supplierName = DB::table('users')->where('id', $confirmedRate->user_id)->first();
                    }
                    else{
                        $supplierName = '';
                    }
                    $salesPerson = DB::table('users')->where('id', $extraCost->indent->user_id)->first();
                @endphp

                
            <tr>
                <td>
                    <a href="{{ route('pods.create', ['id' => $extraCost->indent->id]) }}" class="text-primary">{{ $extraCost->indent->getUniqueENQNumber() }}</a>
                </td>
                @if(auth()->user()->role_id != 4)
                    <td>{{ $extraCost->indent->customer_name }}</td>
                    <td>{{ $extraCost->indent->company_name }}</td>
                    <td>{{ $extraCost->indent->number_1 }}</td>
                @endif
                <td>{{ $extraCost->indent->pickup_location_id }}</td>
                <td>{{ $extraCost->indent->drop_location_id }}</td>        
                <td>{{ ($extraCost->indent->materialType) ? $extraCost->indent->materialType->name : 'N/A' }}</td>       
                <td>{{ $extraCost->indent->truckType->name }}</td>        
                <td>{{ $extraCost->indent->body_type }}</td>        
                <td>{{ $extraCost->indent->weight }} {{ $extraCost->indent->weight_unit }}</td>        
                <td>{{ $extraCost->indent->driver_rate }}</td> 
                @if(auth()->user()->role_id != 4)
                <td>{{ $extraCost->indent->customer_rate }}</td>
                @endif
                <td>{{ $extraCost->indent->remarks }}</td>
                <td>{{ ($salesPerson) ? $salesPerson->name : 'N/A' }}</td>
                @if(auth()->user()->role_id != 3)
                <td>{{ ($supplierName) ? $supplierName->name : 'N/A' }}</td>
                @endif
                <td>{{ $extraCost->extra_cost_type }}</td>
                <td>{{ $extraCost->amount }}</td>
                <td>
                    <a href="{{ route('extra_costs.edit', $extraCost->id) }}" class="text-info"> <i class="fas fa-edit" style="font-size:8px;color:#007bff;"></i></a>
                    <form action="{{ route('extra_costs.destroy', $extraCost->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"  style="font-size:8px;color:red;"></i></button>
                    </form>
                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 4)
                        <a href="{{ route('driver-details', ['id' => $extraCost->indent->id]) }}" class="text-primary me-2">
                            <i class="fa fa-eye" style="font-size:8px;color:darkblue"></i>
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
<script>
    setTimeout(function(){
       location.reload();
    }, 60000); // 10000 milliseconds = 10 seconds
</script>