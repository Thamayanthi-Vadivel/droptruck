<!-- resources/views/indents/status6.blade.php -->

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

    .bg-gradient-info {
        background-image: radial-gradient(515px at 48.7% 52.8%, rgb(239, 110, 110) 0%, rgb(230, 25, 25) 46.5%, rgb(154, 11, 11) 100.2%);
    }
</style>
<div class="m-3">
        <a class="btn btn-warning" href="{{ route('accounts.ongoing') }}" style="font-size: 12px; padding: 5px 10px;">Ongoing</a>
        <a class="btn btn-warning" href="{{route('pendingtrips')}}" style="font-size: 12px; padding: 5px 10px;">Complete trips with pending</a>
        <a href="{{route('accounts.completetrips')}}" class="btn btn-warning custom-active" style="font-size: 12px; padding: 5px 10px; position: relative;">Complete Trips<span class="badge badge-primary circle-badge text-light" id="completedIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
        {{ $indentsCount }}
    </span></a>
        <a href="{{ route('export-to-excel') }}" class="btn btn-sm float-end btn-success">Export to Excel</a>
    </div>
<div class="container mt-3">

    <table class="table table-bordered table-striped table-hover" style="font-size:8px;">
        <thead>
            <tr>
                <th class="bg-gradient-info text-light">ID</th>
                <th class="bg-gradient-info text-light">Pickup Location</th>
                <th class="bg-gradient-info text-light">Drop Location</th>
                <th class="bg-gradient-info text-light">Driver Name</th>
                <th class="bg-gradient-info text-light">Driver Number</th>
                <th class="bg-gradient-info text-light">Vehicle Number</th>
                <th class="bg-gradient-info text-light">Customer Advances</th>
                <th class="bg-gradient-info text-light">Customer Balance Amount</th> <!-- New column for Customer Balance Amount -->
                <th class="bg-gradient-info text-light">Supplier Advances</th>
                <th class="bg-gradient-info text-light">Supplier Balance Amount</th> <!-- New column for Supplier Balance Amount -->
                <th class="bg-gradient-info text-light">Extra Costs</th>
                <th class="bg-gradient-info text-light">Sales Name</th>
                <th class="bg-gradient-info text-light">Supplier Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($indents as $indent)
                @foreach ($indent->driverDetails as $driverDetail)
                    @php 
                        $supplierDetails = $indent->suppliers->first();

                        $confirmedRate = DB::table('rates')->where('indent_id',$indent->id)->where('is_confirmed_rate', 1)->first();
                        if($confirmedRate) {
                            $supplierNames = DB::table('users')->where('id', $confirmedRate->user_id)->first();
                        } else {
                            $supplierNames = '';
                        }
                        
                        $supplierName = DB::table('suppliers')->where('indent_id', $indent->id)->first();
                        
                    @endphp
                    <tr>
                        <td>{{ $indent->getUniqueENQNumber() }}</td>
                        <td>{{ $indent->pickup_location_id }}</td>
                        <td>{{ $indent->drop_location_id }}</td>
                        <td>{{ $driverDetail->driver_name }}</td>
                        <td>{{ $driverDetail->driver_number }}</td>
                        <td>{{ $driverDetail->vehicle_number }}</td>
                        <td>{{ $indent->customerAdvances->sum('advance_amount') }}</td>
                        <td>{{ optional($indent->customerAdvances->last())->balance_amount }}</td>
                        <td>{{ $indent->supplierAdvances->sum('advance_amount') }}</td>
                        <td>{{ optional($indent->supplierAdvances->last())->balance_amount }}</td>
                        <td>
                            @foreach ($indent->extraCosts as $extraCost)
                                 {{ $extraCost->amount }}<br>
                            @endforeach
                        </td>
                        <td>{{ ($indent->user) ? $indent->user->name : 'N/A' }}</td>
                        <td>{{ ($supplierName) ? $supplierName->supplier_name : 'N/A' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $indents->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
</div>
@endsection
