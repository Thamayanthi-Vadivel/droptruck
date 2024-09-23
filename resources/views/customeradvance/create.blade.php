@extends('layouts.sidebar')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="card mx-auto" style="width: 30rem;">
        <div class="card-header">
            <h2 class="text-center">Create Customer Advance</h2>
        </div>
        <div class="card-body">
            <!-- Customer Advance Create Form -->
            <form method="POST" action="{{ route('customer_advances.store') }}">
                @csrf
                <div class="mb-3 float-end">
                    <input type="hidden" class="form-control" id="indent_id" name="indent_id" value="{{ $indent->id }}" required>
                    <span class="form-text btn btn-danger">{{ $indent->getUniqueENQNumber() }}</span> <!-- Display the indent_id if needed -->
                </div><br>
                <div class="mb-3">
                    <div class="form-group mt-1">
                        <label for="payment_terms">Payment Mode</label>
                        <div class="input-group">
                            <select name="payment_terms" id="payment_terms" class="form-select form-select-sm">
                                <option value="">select</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                </div><br>
                <label for="payment_terms">Customer Advance Amount</label>
                    <input type="text" class="form-control" id="advance_amount" name="advance_amount" placeholder="Enter the Customer Advance Amount">
                </div>
                <!-- Add other form fields as needed -->

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JavaScript (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
