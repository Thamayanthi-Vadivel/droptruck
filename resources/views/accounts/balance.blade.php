<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Truck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        th {
            color: blueviolet;
        }

        .btn-warning.custom-active {
            background: linear-gradient(135deg, #007bff, #8a2be2);
            color: #fff;
            border: #8a2be2;
        }
    </style>
</head>

<body>
    <div class="m-3">
        <a class="btn btn-warning custom-active" href="{{ route('accounts.ongoing') }}" style="font-size: 12px; padding: 5px 10px;">Ongoing</a>
        <a class="btn btn-warning" href="{{route('pendingtrips')}}" style="font-size: 12px; padding: 5px 10px;">Complete trips without pending</a>
        <a class="btn btn-warning" href="{{route('accounts.completetrips')}}" style="font-size: 12px; padding: 5px 10px;">Complete Trips</a>
    </div>
    <div class="card container-fluid" style="font-size:12px;">
        <div class="row mt-2">
            <div class="col-lg-6">
                <div class="mt-2">
                    <h5 class="btn bg-primary fw-bolder text-white float-end d-inline" style="font-size:12px;">{{ optional($drivers->indent)->getUniqueENQNumber() }}</h5>
                </div>
                <div class="mt-2">
                    <h4 class="d-flex fw-bolder mt-1">
                        <span class="text-success fw-bolder me-2">{{ optional(optional($drivers->indent)->pickupLocation)->district }}</span> - <span class="text-danger fw-bolder ms-2">{{ optional(optional($drivers->indent)->dropLocation)->district }}</span>
                    </h4>
                </div>
                <table class="table table-bordered mb-2">
                    <tr>
                        <td>CUSTOMER</td>
                        <td>SUPPLIER</td>
                        <td>TRUCK</td>
                    </tr>
                    <tbody>
                        <tr>
                            <th>{{ optional($drivers->indent)->customer_name }}</th>
                            <th>{{ optional($drivers->supplier)->supplier_name }}</th>
                            <th>{{ optional(optional($drivers->indent)->TruckType)->name }}</th>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered">
                    <tr>
                        <td> Customer Price
                            <a href="{{ route('customer-rate.edit', ['indent_id' => $drivers->indent->id]) }}">
                                <i class="fa fa-edit" style="font-size: 10px; color: orange; margin-left: 5px;"></i>
                            </a>
                        </td>

                        <td>Ton</td>
                        <td> {{ optional($drivers->indent)->weight }}</td>
                    </tr>
                    <tbody>
                        <tr>
                            <th>
                                @if($drivers->indent->customerRate)
                                {{ $drivers->indent->customerRate->rate }}
                                @else
                                N/A
                                @endif
                            </th>
                            <td>Supplier price</td>
                            <td>
                                @if($drivers->indent->indentRate->isNotEmpty())
                                {{ $drivers->indent->indentRate->sortBy('rate')->first()->rate }}
                                @else
                                N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="mb-3">
                    <table class="table table-bordered">
                        <tr>
                            <td><b>ORDER DATE</b></td>
                            <td><b>ETA</b></td>
                            <td><b>SOURCE</b></td>
                            <td><b>DESTINATION</b></td>
                        </tr>
                        <tr>
                            <td>{{ optional(optional($drivers->indent)->created_at)->format('d/m/Y') }}</td>
                            <td>On The Road</td>
                            <td>{{ optional(optional($drivers->indent)->pickupLocation)->district }}</td>
                            <td>{{ optional(optional($drivers->indent)->dropLocation)->district }}</td>
                        </tr>
                    </table>
                </div>
                <!-- <div>
                    <strong>Pod</strong>
                    @if ($drivers->indent && $drivers->indent->pods && $drivers->indent->pods->isNotEmpty())
                    @foreach ($drivers->indent->pods as $pod)
                    <a href="{{ route('pods.edit', ['pod' => $pod->id]) }}" class="me-4 text-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endforeach
                    @else
                    <span>No pods available</span>
                    @endif
                </div> -->
            </div>


            <div class="col-lg-6">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-info">Charges Type</th>
                        <th class="bg-info">Customer</th>
                        <th class="bg-info">Supplier</th>
                        <th class="bg-info">Action</th>
                    </tr>
                    <tr>
                        <td>Unloading</td>
                        <td class="text-success">
                            @if ($drivers->indent->extraCosts->count() > 0 && $drivers->indent->extraCosts->first()->amount !== null && $drivers->indent->extraCosts->first()->amount !== 0)
                            <span>&#8377;</span>{{ $drivers->indent->extraCosts->first()->amount }}
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-success">
                            @if ($drivers->indent->extraCosts->count() > 0 && $drivers->indent->extraCosts->first()->amount !== null && $drivers->indent->extraCosts->first()->amount !== 0)
                            <span>&#8377;</span>{{ $drivers->indent->extraCosts->first()->amount }}
                            @else
                            N/A
                            @endif
                        </td>
                        <td><i class="fa fa-edit" style="font-size:10px;color:orange;margin-left:5px;"></i><i class="fas fa-times-circle" style="font-size:10px;color:red;margin-left:5px;"></i></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td class="text-success">
                            @if($drivers->indent->customerRate)
                            <span> &#8377</span> {{ $drivers->indent->customerRate->rate }}
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-success">
                            @if($drivers->indent->indentRate->isNotEmpty())
                            {{ $drivers->indent->indentRate->sortBy('rate')->first()->rate }}
                            @else
                            N/A
                            @endif
                        </td>
                        <td><i class="fa fa-edit" style="font-size:10px;color:orange;margin-left:5px;"></i><i class="fas fa-times-circle" style="font-size:10px;color:red;margin-left:5px;"></i></td>
                    </tr>
                    <tr>
                        <td>Payment Manual</td>
                        <td class="text-success"><span> &#8377</span>N/A</td>
                        <td class="text-success"><span> &#8377</span>N/A</td>
                        <td><i class="fa fa-edit" style="font-size:10px;color:orange;margin-left:5px;"></i><i class="fas fa-times-circle" style="font-size:10px;color:red;margin-left:5px;"></i></td>
                    </tr>
                </table>
                <p class="fw-bolder">SUPPLIER PAYMENTS
                    <span class="float-end text-danger fw-bolder">
                        @if($drivers->indent->supplierAdvances->isNotEmpty())
                        {{ $drivers->indent->supplierAdvances->sum('advance_amount') }}
                        @else
                        N/A
                        @endif
                    </span>
                </p>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-info">type</th>
                        <th class="bg-info">Date</th>
                        <th class="bg-info">Mode</th>
                        <th class="bg-info">Amount</th>
                        <th class="bg-info">Total</th>
                        <th class="bg-info">Action</th>
                    </tr>
                    @foreach($drivers->indent->supplierAdvances as $advance)
                    <tr>
                        <td>Advance</td>
                        <td>{{ optional($advance->created_at)->format('d/m/Y') }}</td>
                        <td>Cash</td>
                        <td>{{ $advance->advance_amount }}</td>
                        <td><span> &#8377</span>{{ $advance->advance_amount }}</td>
                        <td>
                            @if($advance->balance_amount <= 0) <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#advanceModal{{ $advance->indent->id }}" disabled>
                                Paid
                                </button>

                                @else
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#advanceModal{{ $advance->indent->id }}">
                                    Pay
                                </button>
                                @endif

                                <!-- Modal -->
                                <div class="modal fade" id="advanceModal{{ $advance->indent->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Enter Customer Advance</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Your form for entering advance amount here -->
                                                <!-- Example: -->
                                                <form action="{{ route('supplier_advances.store', ['indent_id' => $advance->indent->id]) }}" method="post">
                                                    @csrf
                                                    <label for="advanceAmount">Advance Amount:</label>
                                                    <input type="text" name="advance_amount" id="advanceAmount" class="form-control">
                                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        @if($drivers->indent->supplierAdvances->isNotEmpty())
                    <tr>
                        <th>Balance Amount</th>
                        <td>
                            {{ optional($drivers->indent->indentRate->last())->rate - $drivers->indent->supplierAdvances->sum('advance_amount') }}
                        </td>
                    </tr>
                    @else
                    <tr>
                        <th>Balance Amount</th>
                        <td>
                            @if($drivers->indent->indentRate->isNotEmpty())
                            {{ $drivers->indent->indentRate->sortBy('rate')->last()->rate }}
                            @else
                            N/A
                            @endif

                        </td>
                    </tr>
                    @endif

                    </tr>
                </table>

                <p class="fw-bolder">CUSTOMER PAYMENTS
                    <span class="float-end text-danger fw-bolder">
                        <span class="float-end text-danger fw-bolder">
                            @if($drivers->indent->customerAdvances->isNotEmpty())
                            {{ $drivers->indent->customerAdvances->sum('advance_amount') }}
                            @else
                            N/A
                            @endif
                        </span>
                </p>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-info">type</th>
                        <th class="bg-info">Date</th>
                        <th class="bg-info">Mode</th>
                        <th class="bg-info">Amount</th>
                        <th class="bg-info">Action</th>
                    </tr>
                    @foreach($drivers->indent->customerAdvances as $payment)
                    <tr>
                        <td>Advance</td>
                        <td>{{ optional($payment->created_at)->format('d/m/Y') }}</td>
                        <td>Cash</td>
                        <td><span>&#8377;</span>{{ $payment->advance_amount }}</td>
                        <td>
                            @if($payment->balance_amount <= 0) <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#customerAdvanceModal{{ $drivers->indent->id }}" disabled>
                                Paid
                                </button>
                                @else
                                <button type="button" class="btn btn-primary btn-sm click" data-toggle="modal" data-target="#customerAdvanceModal{{ $drivers->indent->id }}">
                                    Pay
                                </button>
                                @endif

                                <!-- Modal -->
                                <div class="modal fade" id="customerAdvanceModal{{ $drivers->indent->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Enter Customer Advance</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Form for entering Customer Advance amount -->
                                                <form action="{{ route('customer_advances.store', $drivers->indent->id) }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="indent_id" value="{{ $drivers->indent->id }}">
                                                    <div class="form-group">
                                                        <label for="customerAdvanceAmount">Customer Advance Amount:</label>
                                                        <input type="number" name="advance_amount" id="customerAdvanceAmount" class="form-control" min="0" step="0.01" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </td>
                    </tr>
                    @endforeach

                    <tr>
                        <th>Balance Amount -
                            @php
                            $totalAmount = optional($drivers->indent->customerRate)->rate;
                            $advanceSum = $drivers->indent->customerAdvances->sum('advance_amount');
                            $balanceAmount = $totalAmount - $advanceSum;
                            echo $balanceAmount;
                            @endphp
                        </th>
                    </tr>
                </table>
                <div>
                    <b>Profit Amount</b>
                    @if($drivers->indent->customerRate && $drivers->indent->indentRate->first())
                    {{ $drivers->indent->customerRate->rate  -  $drivers->indent->indentRate->first()->rate}}
                    @else
                    N/A
                    @endif
                </div>

            </div>
        </div>
</body>
<script>
    function handlePayment(button) {
        button.disabled = true;
    }
</script>

</html>