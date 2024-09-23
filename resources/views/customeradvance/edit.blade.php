@extends('layouts.sidebar')

@section('content')
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCustomerAdvanceModal" data-indent-id="{{ $customerAdvance->indent_id }}" data-advance-amount="{{ $customerAdvance->advance_amount }}">
                Edit
            </button>

            <!-- Edit Customer Advance Modal -->
<div class="modal fade" id="editCustomerAdvanceModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerAdvanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerAdvanceModalLabel">Edit Customer Advance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Customer Advance Edit Form -->
                <form method="POST" id="editForm" action="{{ route('customer_advances.update', ['customerAdvance' => 0]) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_indent_id">Indent ID</label>
                        <input type="text" class="form-control" id="edit_indent_id" name="indent_id" required>
                    </div>
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
                    <div class="form-group">
                        <label for="edit_advance_amount">Advance Amount</label>
                        <input type="text" class="form-control" id="edit_advance_amount" name="advance_amount" required>
                    </div>
                    <!-- Add other form fields as needed -->

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
