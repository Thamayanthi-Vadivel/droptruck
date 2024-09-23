@extends('layouts.sidebar')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
@if(session('error'))
    <div class="alert alert-success">
        {{ session('error') }}
    </div>
@endif

<div class="m-3">
    <a class="btn btn-warning custom-active" href="{{ route('accounts.ongoing') }}" style="font-size: 12px; padding: 5px 10px;">Ongoing</a>
    <a class="btn btn-warning" href="{{route('pendingtrips')}}" style="font-size: 12px; padding: 5px 10px;">Complete trips with pending</a>
    <a class="btn btn-warning" href="{{route('accounts.completetrips')}}" style="font-size: 12px; padding: 5px 10px;">Complete Trips</a>
</div>
<table class="table table-bordered table-striped table-hover" style="font-size:8px;">
    <thead>
        <tr>
            <th class="bg-gradient-info text-light">Enq Number</th>
            <th class="bg-gradient-info text-light">Supplier Name</th>
            <th class="bg-gradient-info text-light">Supplier Type</th>
            <th class="bg-gradient-info text-light">Company Name</th>
            <th class="bg-gradient-info text-light">Contact Number</th>
            <th class="bg-gradient-info text-light">Pan Card</th>
            <th class="bg-gradient-info text-light">Sales Name</th>
        </tr>
    </thead>
    <tbody>
        @php
            $shownENQNumbers = [];
        @endphp

        @foreach ($suppliers as $supplier)
            @php
                $driverDetails = optional($supplier->indent)->driverDetails;
                
                $confirmedRate = DB::table('rates')->where('indent_id',$supplier->indent->id)->where('is_confirmed_rate', 1)->first();
                if($confirmedRate) {
                    $supplierNames = DB::table('users')->where('id', $confirmedRate->user_id)->first();
                } else {
                    $supplierNames = '';
                }
                
                $supplierName = DB::table('suppliers')->where('indent_id', $supplier->indent->id)->first();
                
            @endphp

            {{-- @foreach ($driverDetails as $driverDetail) --}}
                @php
                    $uniqueENQNumber = optional($supplier->indent)->getUniqueENQNumber();
                  
                    $cleanedENQNumber = trim($uniqueENQNumber); // Normalize the ENQ number
                @endphp

                {{-- @if ($cleanedENQNumber && !in_array($cleanedENQNumber, $shownENQNumbers)) --}}
                    <tr>
                        <td>
                            <a href="{{ route('accounts.index', ['id' => $supplier->indent->id]) }}" class="text-decoration-none">
                                {{ $cleanedENQNumber }}
                            </a>
                        </td>
                        <td>{{ $supplier->supplier_name }}</td>
                        <td>{{ $supplier->supplier_type }}</td>
                        <td>{{ $supplier->company_name }}</td>
                        <td>{{ $supplier->contact_number }}</td>
                        <td>{{ $supplier->pan_card_number }}</td>
                        <td>{{ ($supplier->indent->user) ? $supplier->indent->user->name : 'N/A' }}</td>
                    </tr>

                    @php
                        $shownENQNumbers[] = $cleanedENQNumber;
                    @endphp
                {{-- @endif --}}
            {{-- @endforeach --}}
        @endforeach

    </tbody>
</table>
@endsection