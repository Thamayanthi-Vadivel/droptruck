@extends('layouts.sidebar')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<?php //echo 'sdsd<pre>'; print_r($extraCostDetails); exit; ?>

<div class="container mt-5">
     <div class="col">
        <div class="d-flex">
            <button type="button" class="btn dash1"  style="margin-left:600px">
            <a href="/unloading" class="text-decoration-none text-dark"> Back</a>
        </button>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header text-white dash1">
            @if((empty($extraCostDetails)) && (auth()->user()->role_id == 4 || auth()->user()->role_id == 1 || auth()->user()->role_id == 2))
                <h2 class="card-title">Create Extra Cost</h2>
            @else
                <h2 class="card-title">Extra Cost Details</h2>
            @endif
        </div>
        <div class="card-body">
            @if((empty($extraCostDetails)) && (auth()->user()->role_id == 4 || auth()->user()->role_id == 1 || auth()->user()->role_id == 2))
            <form action="{{ route('extra_costs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <input type="hidden" name="indent_id" value="{{ $indent->id }}">
                    <button class="btn btn-danger float-end">{{ $indent->getUniqueENQNumber() }}</button>
                </div>
                <div class="row d-flex justify-content-center gap-3 mt-4">
                    <div class="form-group col-lg-3">
                        <label for="extra_cost_type">Extra Cost Type:</label>
                        <select name="extra_cost_type[]" id="extra_cost_type" class="form-select" multiple size="4" style="overflow-y: auto;">
                            <option value="None">None</option>
                            <option value="Labor">Labor</option>
                            <option value="Halt">Halt</option>
                            <option value="Over Load">Over Load</option>
                        </select>
                    </div>



                    <div class="form-group col-lg-3">
                        <label for="amount">Amount:</label>
                        <input type="text" name="amount" class="form-control">
                    </div>
                </div>
                <div class="row d-flex justify-content-center gap-3 mt-4">
                    <div class="form-group col-lg-3">
                        <label for="bill_copy">Bill Copy:</label>
                        <input type="file" name="bill_copy[]" class="form-control" multiple>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="unloading_photo">Unloading Photo:</label>
                        <input type="file" name="unloading_photo" class="form-control">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="bill_copies">Bill Copies Information:</label>
                        <input type="file" name="bill_copies[]" class="form-control" multiple>
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <button type="submit" class="btn dash1">Submit</button>
                </div>
            </form>
            @else
                <div class="section">
                    <!-- <h3>Extra Cost Details</h3> -->
                    <ul class="list-unstyled">
                        <li class="row">
                            <strong class="col-sm-3">Extra Cost Type</strong>
                            <span class="col-sm-7">{{ ($extraCostDetails) ? $extraCostDetails->extra_cost_type : 'N/A' }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Amount</strong>
                            <span class="col-sm-7">{{ ($extraCostDetails) ? $extraCostDetails->amount : 'N/A' }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Bill Copy</strong>
                            <span class="col-sm-7">
                                @if($extraCostDetails)
                                    <a href="{{ asset('/' . $extraCostDetails->bill_copy) }}" target="_blank" class="text-decoration-underline">
                                        Bill Copy
                                    </a>
                                    <a href="{{ asset('/' . $extraCostDetails->bill_copy) }}" download="{{ asset('/' . $extraCostDetails->bill_copy) }}">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    'N/A'
                                @endif
                                <br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Unloading Photo</strong>
                            <span class="col-sm-7">
                                @if($extraCostDetails)
                                    <a href="{{ asset('/' . $extraCostDetails->unloading_photo) }}" target="_blank" class="text-decoration-underline">
                                        Unloading Photo
                                    </a>
                                    <a href="{{ asset('/' . $extraCostDetails->unloading_photo) }}" download="{{ asset('/' . $extraCostDetails->unloading_photo) }}">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    'N/A'
                                @endif
                                    <br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Bill Copies</strong>
                            <span class="col-sm-7">
                                @if($extraCostDetails)
                                    <a href="{{ asset('/' . $extraCostDetails->bill_copies) }}" target="_blank" class="text-decoration-underline">
                                        Bill Copies
                                    </a>
                                    <a href="{{ asset('/' . $extraCostDetails->bill_copies) }}" download="{{ asset('/' . $extraCostDetails->bill_copies) }}">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    'N/A'
                                @endif
                                <br>
                            </span>
                        </li>
                    </ul>
                </div>
        </div>
        @endif
    </div>
</div>

<script>
    function handleExtraCostTypeChange() {
        var extraCostType = document.getElementById('extra_cost_type').value;
        var amountInput = document.getElementsByName('amount')[0];
        var otherInputs = document.querySelectorAll('input[type=file], input[type=text]');

        if (extraCostType === 'None') {
            amountInput.value = '0';
            otherInputs.forEach(function(input) {
                input.style.display = 'none';
            });
        } else {
            amountInput.value = '';
            otherInputs.forEach(function(input) {
                input.style.display = 'block';
            });
        }
    }

    // Event listener for change in extra cost type
    document.getElementById('extra_cost_type').addEventListener('change', handleExtraCostTypeChange);

    // Ensure initial state on page load
    window.addEventListener('DOMContentLoaded', function() {
        handleExtraCostTypeChange();
    });
</script>



@endsection