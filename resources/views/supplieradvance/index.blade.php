@extends('layouts.sidebar')

@section('content')

<!-- Your existing content -->

@foreach($supplierAdvances as $supplierAdvance)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Supplier Advance</h5>
            <p class="card-text">Indent ID: {{ $supplierAdvance->indent_id }}</p>
            <p class="card-text">Advance Amount: {{ $supplierAdvance->advance_amount }}</p>

            <!-- Edit Button -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editSupplierAdvanceModal" data-indent-id="{{ $supplierAdvance->indent_id }}" data-advance-amount="{{ $supplierAdvance->advance_amount }}">
                Edit
            </button>

            <!-- Delete Button (optional) -->
            <form action="{{ route('supplier_advances.destroy', ['supplierAdvance' => $supplierAdvance->id]) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@endforeach

<!-- Create and Edit Modals (unchanged) -->

@endsection
