@extends('layouts.app')

@section('content')

<!-- Your existing content -->

@foreach($customerAdvances as $customerAdvance)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Customer Advance</h5>
            <p class="card-text">Indent ID: {{ $customerAdvance->indent_id }}</p>
            <p class="card-text">Advance Amount: {{ $customerAdvance->advance_amount }}</p>

            <!-- Edit Button -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCustomerAdvanceModal" data-indent-id="{{ $customerAdvance->indent_id }}" data-advance-amount="{{ $customerAdvance->advance_amount }}">
                Edit
            </button>

            <!-- Delete Button (optional) -->
            <form action="{{ route('customer_advances.destroy', ['customerAdvance' => $customerAdvance->id]) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@endforeach
@endsection
