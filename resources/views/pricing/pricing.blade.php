@extends('layouts.sidebar')

@section('content')
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col"> </div>
        <div class="col-auto">
            <button type="button" class="btn dash1 mt-3">
                <a href="{{ route('pricings.create') }}" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#pricingModal"> create</a>
            </button>
            @include('pricing.create')
        </div>
    </div>
</div>
<div>
    @include('pricing.index') <!-- Check if the inclusion of 'pricing.index' causes the issue -->
</div>
@endsection
