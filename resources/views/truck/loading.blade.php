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
@if(Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

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
        <a class="btn btn-warning custom-active" href="{{route('loading')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">On The Road
        <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $suppliers->count() }}
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
        <a class="btn btn-warning" href="{{route('pods.index')}}" style="font-size: 12px; padding: 5px 10px;position:relative;">Complete Trips
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $completedTrips }}
    </span>
        </a>
    </div>
</div>
<table class="table table-bordered table-striped table-hover" style="font-size:8px;">
    <thead>
        <tr>
            <th class="bg-gradient-info text-light">Enq No</th>
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
            <th class="bg-gradient-info text-light">Supplier Type</th>
            <th class="bg-gradient-info text-light">Supplier's Company Name</th>
            @endif
            @if(auth()->user()->role_id != 4)
            <th class="bg-gradient-info text-light">Customer Advances</th>
            @endif
            <th class="bg-gradient-info text-light">Supplier Advances</th>
             <th class="bg-gradient-info text-light">Crated Date</th>
            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 4)
                <th class="bg-gradient-info text-light">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($suppliers as $supplier)
            @php
                
                $confirmedRate = DB::table('rates')->where('indent_id',$supplier->indent->id)->where('is_confirmed_rate', 1)->first();
                
                if($confirmedRate) {
                    $supplierName = DB::table('users')->where('id', $confirmedRate->user_id)->first();
                }
                else{
                    $supplierName = '';
                }
 
                $salesPerson = DB::table('users')->where('id', $supplier->indent->user_id)->first();
            @endphp
        <tr>
            <td>
                @if(auth()->user()->role_id == 4)
                    <a href="{{ route('driver-details', ['id' => $supplier->indent_id]) }}" class="text-primary me-2">
                        {{ optional($supplier->indent)->getUniqueENQNumber() ?? '' }}
                    </a>
                @else
                    <a href="{{ route('driver-details', ['id' => $supplier->indent_id]) }}" class="text-primary me-2">
                        {{ optional($supplier->indent)->getUniqueENQNumber() ?? '' }}
                    </a>
                    {{-- optional($supplier->indent)->getUniqueENQNumber() ?? '' --}}
                @endif
            </td>
            @if(auth()->user()->role_id != 4)
                <td>{{ $supplier->indent->customer_name }}</td>
                <td>{{ $supplier->indent->company_name }}</td>
                <td>{{ $supplier->indent->number_1 }}</td>
            @endif
            <td>{{ $supplier->indent->pickup_location_id }}</td>
            <td>{{ $supplier->indent->drop_location_id }}</td>        
            <td>{{ ($supplier->indent->materialType) ? $supplier->indent->materialType->name : 'N/A' }}</td>        
            <td>{{ $supplier->indent->truckType->name }}</td>        
            <td>{{ $supplier->indent->body_type }}</td>        
            <td>{{ $supplier->indent->weight }} {{ $supplier->indent->weight_unit }}</td>        
            <td>{{ $supplier->indent->driver_rate }}</td> 
            @if(auth()->user()->role_id != 4)
            <td>{{ $supplier->indent->customer_rate }}</td>
            @endif
            <td>{{ $supplier->indent->remarks }}</td>
            @if(auth()->user()->role_id != 3)
            <td>{{ ($salesPerson) ? $salesPerson->name : 'N/A' }}</td>
            <td>{{ ($supplierName) ? $supplierName->name : 'N/A' }}</td>
            <td>{{ $supplier->supplier_type }}</td>
            @endif
            <td>{{ $supplier->company_name }}</td>
            @if(auth()->user()->role_id != 4)
            <td>
                @forelse($supplier->indent ? $supplier->indent->customerAdvances : [] as $customerAdvance)
                    {{ $customerAdvance->advance_amount }}
                    {{-- Add more details if needed --}}
                @empty
                    n/a
                @endforelse

            </td>
            @endif
            <td>
                @forelse(optional($supplier->indent)->supplierAdvances ?? [] as $supplierAdvance)
                    {{ $supplierAdvance->advance_amount }}
                    {{-- Add more details if needed --}}
                @empty
                    n/a
                @endforelse
            </td>
            <td>{{ $supplier->indent->created_at }}</td>
            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 4)
                <td><a href="{{ route('driver-details', ['id' => $supplier->indent->id]) }}" class="text-primary me-2">
                    <i class="fa fa-eye" style="font-size:8px;color:darkblue"></i>
                </a></td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
<script>
    setTimeout(function(){
       location.reload();
    }, 60000); // 10000 milliseconds = 10 seconds
</script>