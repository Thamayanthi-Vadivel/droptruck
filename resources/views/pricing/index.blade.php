@extends('layouts.sidebar')

@section('content')
<div class="d-flex justify-content-end gap-2">
    <div>
        <form class="form-inline mt-3" type="get" action="{{ url('/search/pricing') }}">
            <input class="form-control" name="query" type="search" placeholder="search...">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
    </div>
    <div>
        <button type="button" class="btn dash1 mt-3">
            <a href="{{ route('pricings.create') }}" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#pricingModal">Create Pricing</a>
        </button>
        @include('pricing.create')
    </div>
    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
        <div>
            <a href="{{ route('pricing-export') }}" class="btn btn-sm float-end btn-success mt-3">Pricing Report</a>
        </div>
    @endif
</div>

<table class="table  table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pickup City</th>
            <th>Drop City</th>
            <th>Vehicle Type</th>
            <th>Body Type</th>
            <th>Amount From</th>
            <th>Amount To</th>
            <th>Remarks</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach($pricings as $pricing)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $pricing->pickup_city }}</td>
            <td>{{ $pricing->drop_city }}</td>
            <td>{{ ($pricing->truckType) ? $pricing->truckType->name : 'N/A' }}</td>
            <td>{{ $pricing->body_type }}</td>
            <td>{{ ($pricing->rate_from) ? $pricing->rate_from : 'N/A' }}</td>
            <td>{{ $pricing->rate_to }}</td>
            <td>{{$pricing->remarks}}</td>
            <td>
                @if($pricing->id)
                <div>@include('pricing.edit')</div>
                
                <a href="{{ route('pricings.show', ['pricing' => $pricing]) }}">
                    <i class="fa fa-eye" style="font-size:17px"></i>
                </a>
                
                <div>@include('pricing.delete')</div>
                @endif
            </td>
        </tr>
        @php
            $i++;
        @endphp
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $pricings->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
@endsection
