@extends('layouts.sidebar')

@section('content')
<div class="card mx-auto" style="width: 500px; margin-top:150px;">
<div class="card-header dash1">
        Edit Supplier Advance
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('supplier_advances.update', ['supplierAdvance' => $supplierAdvance->id]) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_indent_id">Indent ID</label>
                <input type="text" class="form-control" id="edit_indent_id" name="indent_id" value="{{ $supplierAdvance->indent_id }}" required>
            </div><br>
            <div class="mb-3">
                <div class="form-group mt-1">
                    <label for="payment_terms">Payment Mode</label>
                    <div class="input-group">
                        <select name="payment_terms" id="payment_terms" class="form-select form-select-sm">
                            <option value="">select</option>
                            <option value="Cash" {{ $supplierAdvance->payment_type === 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Bank" {{ $supplierAdvance->payment_type === 'Bank' ? 'selected' : '' }}>Bank</option>
                            <option value="Cheque" {{ $supplierAdvance->payment_type === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="edit_advance_amount">Advance Amount</label>
                <input type="text" class="form-control" id="edit_advance_amount" name="advance_amount" value="{{ $supplierAdvance->advance_amount }}" required>
            </div>
            <!-- Add other form fields as needed -->

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        <form action="{{ route('supplier_advances.destroy', ['supplierAdvance' => $supplierAdvance->id]) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</div>
@endsection
