@extends('layouts.sidebar')

@section('content')
<style>
    th {
        color: blueviolet;
    }

    .btn-warning.custom-active {
        background: linear-gradient(135deg, #007bff, #8a2be2);
        color: #fff;
        border: #8a2be2;
    }

    .circle-badge {
        border-radius: 50%;
    }
    .bg-gradient-info {
        background-image: radial-gradient(515px at 48.7% 52.8%, rgb(239, 110, 110) 0%, rgb(230, 25, 25) 46.5%, rgb(154, 11, 11) 100.2%);
    }

</style>
<div>
        <h2 class="btn btn-primary text-white fw-bolder float-end mt-1">User : {{ auth()->user()->name }}</h2>
    </div>
<div class="m-3">
    <a href="{{ route('indents.index') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px; position: relative;">Unquoted<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $unquotedIndents->count() }}
    </span></a>
    <a href="{{ route('fetch-last-two-details') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px; position: relative;">Quoted<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $quotedIndents->count() }}
    </span></a>
    <a href="{{ route('confirmed_locations')}}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Confirmed<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $confirmedIndents }}
    </span></a>
    <a href="{{ route('canceled-indents') }}" class="btn btn-warning custom-active" style="font-size: 12px; padding: 5px 10px; position: relative;">
    Cancel
    <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $canceledIndentsCount }}
    </span>
</a>
    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 3) 
            <a href="{{ route('followup-indents') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Followup
                <span class="badge badge-primary circle-badge text-light" id="followupIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
                {{ $followupIndents->count() }}
                </span>
            </a>
        @endif


</div>

<table class="table table-bordered table-stripped" style="font-size:8px;">
    <thead>
        <tr>
            <th class="bg-gradient-info text-light">ENQ Number</th>
            @if(auth()->user()->role_id != 4)
            <th class="bg-gradient-info text-light">Company Name</th>
            <th class="bg-gradient-info text-light">Customer Name</th>
            <th class="bg-gradient-info text-light">Customer Number</th>
            @endif
            <th class="bg-gradient-info text-light">Pickup Location</th>
            <th class="bg-gradient-info text-light">Drop Location</th>
            <th class="bg-gradient-info text-light">Truck type</th>
            <th class="bg-gradient-info text-light">Body type</th>
            <th class="bg-gradient-info text-light">Weight</th>
            <th class="bg-gradient-info text-light">Material Type</th>
            <th class="bg-gradient-info text-light">HardCopy</th>
            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                <th class="bg-gradient-info text-light">Salesperson</th>
            @endif
            <th class="bg-gradient-info text-light">Created By</th>
            <th class="bg-gradient-info text-light">Reason</th>
            <th class="bg-gradient-info text-light">Created Date</th>
            <th class="bg-gradient-info text-light">Cancelled Date</th>
            <th class="bg-gradient-info text-light">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($canceledIndents as $indent)
            @php
                if($indent->new_material_type == null) {
                    $materialType = ($indent->materialType) ? $indent->materialType->name : 'N/A';
                } else {
                    $materialType = $indent->new_material_type;
                }
            @endphp
        <tr>
            <td>{{ $indent->getUniqueENQNumber() }}</td>
            @if(auth()->user()->role_id != 4)
            <td>{{ $indent->company_name }}</td>
            <td>{{ $indent->customer_name }}</td>
            <td>{{ $indent->number_1 }}</td>
            @endif
            <td>{{ $indent->pickup_location_id ? $indent->pickup_location_id : 'N/A' }}</td>
            <td>{{ $indent->drop_location_id ? $indent->drop_location_id : 'N/A' }}</td>
            <td>{{ $indent->truckType ? $indent->truckType->name : 'N/A' }}</td>
            <td>{{ $indent->body_type }}</td>
            <td>{{ $indent->weight }} {{ $indent->weight_unit }}</td>
            <td>{{ $materialType }}</td>
            <td>{{ $indent->pod_soft_hard_copy }}</td>
            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
            @php 
                    $confirmedRate = DB::table('rates')->where('indent_id',$indent->id)->first();
                    if($confirmedRate) {
                        $supplierName = DB::table('users')->where('id', $confirmedRate->user_id)->first();
                    } else {
                        $supplierName = '';
                    }
                @endphp

                <td>{{ ($supplierName) ? $supplierName->name : '' }}</td>
                @endif
                <td>{{ ($indent->createdUser) ? $indent->createdUser->name .' - '.$indent->createdUser->designation : '' }}</td>
            <td>
            
        @if(optional($indent->cancelReasons)->isNotEmpty())
            @foreach($indent->cancelReasons as $reason)
            {{ $reason->reason }}
            @endforeach
        @else
        N/A
        @endif
    </td>
        <td>{{ $indent->created_at }}</td>
        <td>{{ $indent->deleted_at }}</td>
            <!-- <td>{{ $indent->cancelReasons->isNotEmpty() ? $indent->cancelReasons->pluck('reason')->implode(', ') : 'No Cancel Reason Available' }}</td> -->
            <td>
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
                <form action="{{ route('restore-canceled-indent', ['id' => $indent->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-sm"><i class="fa fa-recycle" style="font-size:8px;"></i></button>
                </form>
                <a href="{{ route('indents.show', ['indent' => $indent->id, 'page' => '2']) }}" class="btn"><i class="fa fa-eye" style="font-size:8px;color:darkblue"></i></a>
                @else
                N/A
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $canceledIndents->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>

<script>
    setTimeout(function(){
       location.reload();
    }, 60000); // 10000 milliseconds = 10 seconds
</script>
@endsection