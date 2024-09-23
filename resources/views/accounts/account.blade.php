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
      <!-- Include Axios library -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

@php
$confirmed_date = 'N/A';
$updateConfirmedDate = '';
if ($drivers && $drivers->indent->confirmed_date) {
    try {
        $date = new DateTime($drivers->indent->confirmed_date);
        $confirmed_date = $date->format('d/m/Y');
        $updateConfirmedDate = $date->format('Y-m-d');
    } catch (Exception $e) {
        $confirmed_date = 'N/A';
        $updateConfirmedDate = '';
    }
}

@endphp

<body>
    <div class="m-3">
        <a class="btn btn-warning custom-active" href="{{ route('accounts.ongoing') }}" style="font-size: 12px; padding: 5px 10px;">Ongoing</a>
        <a class="btn btn-warning" href="{{route('pendingtrips')}}" style="font-size: 12px; padding: 5px 10px;">Complete trips with pending</a>
        <a class="btn btn-warning" href="{{route('accounts.completetrips')}}" style="font-size: 12px; padding: 5px 10px;">Complete Trips</a>
    </div>
    <input type="hidden" class="form-control" id="is_extra_cost_details" name="is_extra_cost_details" value="{{ ($extraCostDetails) ? '1' : '0' }}">
    <input type="hidden" class="form-control" id="is_extra_cost_confirmed" name="is_extra_cost_confirmed" value="{{ ($extraCostDetails) ? $extraCostDetails->is_confirmed : '0' }}">
    <input type="hidden" class="form-control" id="extra_cost_type" name="extra_cost_type" value="{{ ($extraCostDetails) ? $extraCostDetails->extra_cost_type : '' }}">
    <div class="card container-fluid" style="font-size:12px;">
        <div class="row mt-2">
            <div class="col-lg-6">
                <div class="mt-2">
                    <h5 class="btn bg-primary fw-bolder text-white float-end d-inline" style="font-size:12px;">{{ ($drivers) ? optional($drivers->indent)->getUniqueENQNumber() : '-'}}</h5>
                </div>
                <div class="mt-2">
                    <h4 class="d-flex fw-bolder mt-1">
                        <span class="text-success fw-bolder me-2">{{ ($drivers) ? optional(optional($drivers->indent)->pickupLocation)->district : '-' }}</span> - <span class="text-danger fw-bolder ms-2">{{ ($drivers) ? optional(optional($drivers->indent)->dropLocation)->district : '-' }}</span>
                    </h4>
                </div>
                <table class="table table-bordered mb-2">
                    <tr>
                        <td>CUSTOMER</td>
                        <td>SUPPLIER</td>
                        <td>TRUCK</td>
                        <td>SALES</td>
                    </tr>
                    <tbody>
                        <tr>
                            <th>{{ ($drivers) ? optional($drivers->indent)->customer_name : '' }}</th>
                            <th>
                                @if($drivers)
                                    @if ($drivers->indent && $drivers->indent->suppliers->isNotEmpty())
                                        @php $supplierName = $drivers->indent->suppliers->first(); @endphp
                                    {{ ($supplierName) ? $supplierName->supplier_name : '' }}
                                    @else
                                    N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </th>
                            <th>{{ ($drivers) ? optional(optional($drivers->indent)->TruckType)->name : ''}}</th>
                            <th>{{ ($drivers) ? optional(optional($drivers->indent)->user)->name : ''}}</th>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered">
                    <tr>
                        <td> Customer Price
                           {{--
                                <a href="{{ route('customer-rate.edit', ['indent_id' => $drivers->indent->id]) }}">
                                    <i class="fa fa-edit" style="font-size: 10px; color: orange; margin-left: 5px;"></i>
                                </a>
                           --}} 
                        </td>
                         <td>Supplier price</td>
                        <td>Ton</td>            
                    </tr>
                    <tbody>
                        <tr>
                            <td>
                                @if($drivers)
                                    @if($drivers->indent->customerRate)
                                    {{ $drivers->indent->customerRate->rate }} <a href="#" class="btn edit-button" id="updateCustomerAmountModal" data-id="{{ $drivers->indent->customerRate->id }}" data-amount="{{ $drivers->indent->customerRate->rate }}" data-indent-id="{{ $drivers->indent->id }}"><i class="fa fa-edit" style="font-size:8px;color:brown"></i></a>
                                    @else
                                    N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                                    
                            <td>
                                @if($drivers)
                                    @if($drivers->indent->indentRate->isNotEmpty())
                                    @php 
                                        $supplierPrice = $drivers->indent->indentRate->where('is_confirmed_rate', '1')->first();
                                    @endphp
                                        @if($supplierPrice)
                                            {{  $supplierPrice->rate }} <a href="#" class="btn edit-button" id="updateSupplierAmountModal" data-id="{{ $supplierPrice->id }}" data-amount="{{ $supplierPrice->rate }}" data-indent-id="{{ $drivers->indent->id }}"><i class="fa fa-edit" style="font-size:8px;color:brown"></i></a>
                                        @else
                                        {{ '00.00' }} <a href="#" class="btn edit-button" id="updateSupplierAmountModal" data-id="" data-amount="0" data-indent-id="{{ $drivers->indent->id }}"><i class="fa fa-edit" style="font-size:8px;color:brown"></i></a>
                                        @endif 
                                    @else
                                    N/A
                                    @endif
                                @else 
                                    N/A
                                @endif

                            </td>
                             <td> {{ ($drivers) ? optional($drivers->indent)->weight : '' }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="mb-3">
                    <table class="table table-bordered">
                        <tr>
                            <td><b>ORDER DATE</b></td>
                            <td><b>CONFIRMED DATE</b></td>
                            <td><b>SOURCE</b></td>
                            <td><b>DESTINATION</b></td>
                        </tr>
                        <tr>
                            <td>{{ ($drivers) ? optional(optional($drivers->indent)->created_at)->format('d/m/Y') : '' }}</td>
                            <td>{{ $confirmed_date }} <a href="#" class="btn edit-button" id="updateConfirmedDateModal" data-confirmed-date="{{ $updateConfirmedDate }}" data-indent-id="{{ $drivers->indent->id }}"><i class="fa fa-edit" style="font-size:8px;color:brown"></i></a></td>
                            <td>{{ ($drivers) ? optional($drivers->indent)->pickup_location_id ?? '' : ''}}</td>
                            <td>{{ ($drivers) ? optional($drivers->indent)->drop_location_id ?? '' : '' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- <div>
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
                </div> --}}
                <br><br>
                <div>
                    <b>Tracking Link:</b><br>
                    @if($drivers)
                        <input type="text" class="form-control" name="tracking_link" id="tracking_link" value="{{ $drivers->indent->tracking_link }}">
                    @else
                        <input type="text" class="form-control" name="tracking_link" id="tracking_link" value="">
                    @endif
                    <a href="#" onclick="saveTrackingLink('{{ ($drivers) ? $drivers->indent->id : '' }}'); return false;" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Save</a>
                </div>
            </div>

            <div class="col-lg-6">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-info">Charges Type</th>
                        <th class="bg-info">Customer</th>
                        <th class="bg-info">Supplier</th>
                        <!-- <th class="bg-info">Action</th> -->
                    </tr>
                    <tr>
                        <td>Unloading</td>
                        <td class="text-success">
                            @if($drivers)
                                @if ($drivers->indent->extraCosts->count() > 0 && $drivers->indent->extraCosts->first()->amount !== null && $drivers->indent->extraCosts->first()->amount !== 0)
                                <span>&#8377;</span>{{ $drivers->indent->extraCosts->first()->amount }}
                                @else
                                N/A
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="text-success">
                            @if($drivers)
                                @if ($drivers->indent->extraCosts->count() > 0 && $drivers->indent->extraCosts->first()->amount !== null && $drivers->indent->extraCosts->first()->amount !== 0)
                                <span>&#8377;</span>{{ $drivers->indent->extraCosts->first()->amount }}
                                @else
                                N/A
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <!-- <td><i class="fa fa-edit" style="font-size:10px;color:orange;margin-left:5px;"></i><i class="fas fa-times-circle" style="font-size:10px;color:red;margin-left:5px;"></i></td> -->
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td class="text-success">
                            @if($drivers)
                                @if($drivers->indent->customerRate)
                                <span> &#8377</span> {{ $drivers->indent->customerRate->rate }}
                                @else
                                N/A
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="text-success">
                            @if($drivers)
                                @if($drivers->indent->indentRate->isNotEmpty())
                                    @php 
                                        $supplierPrice = $drivers->indent->indentRate->where('is_confirmed_rate', '1')->first();
                                    @endphp
                                {{ (!empty($supplierPrice)) ? $supplierPrice->rate : '' }}
                                @else
                                N/A
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <!-- <td><i class="fa fa-edit" style="font-size:10px;color:orange;margin-left:5px;"></i><i class="fas fa-times-circle" style="font-size:10px;color:red;margin-left:5px;"></i></td> -->
                    </tr>
                    <!-- <tr>
                        <td>Payment Manual</td>
                        <td class="text-success"><span> &#8377</span>N/A</td>
                        <td class="text-success"><span> &#8377</span>N/A</td>
                        <td><i class="fa fa-edit" style="font-size:10px;color:orange;margin-left:5px;"></i><i class="fas fa-times-circle" style="font-size:10px;color:red;margin-left:5px;"></i></td>
                    </tr> -->
                </table>
                <p class="fw-bolder">SUPPLIER PAYMENTS
                    <a href="{{ route('supplier_advances.create', ['id' => optional($drivers->indent)->id]) }}" class="text-decoration-none text-light btn btn-primary btn-sm" style=" padding: 1px 5px;font-size:12px;">Pay</a>
                  {{-- <a href="{{ route('supplier_advances.edit', ['id' => optional($drivers->indent)->id]) }}" class="text-decoration-none text-light btn btn-primary btn-sm" style="padding: 1px 5px; font-size: 12px;">Edit</a> --}}

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
                        <th class="bg-info">Action</th>
                    </tr>
                    @foreach($drivers->indent->supplierAdvances as $advance)
                    <!-- @php echo 'sds<pre>'; print_r($advance); @endphp -->
                    <tr>
                        <td>Advance</td>
                        <td>{{ optional($advance->created_at)->format('d/m/Y') }}</td>
                        <td>{{($advance->payment_type) ? $advance->payment_type : 'N/A' }}</td>
                        <td><span> &#8377</span>{{ $advance->advance_amount }}</td>
                        <td>
                            <button type="button" class="text-decoration-none text-light btn btn-{{ $advance->balance_amount <= 0 || $advance->advance_amount > 0 ? 'secondary' : 'primary' }} btn-sm" style="padding: 1px 5px; font-size: 12px;" data-toggle="modal" data-target="#advanceModal{{ $advance->indent->id }}" {{ $advance->balance_amount <= 0 || $advance->advance_amount > 0 ? 'disabled' : '' }}>
                                {{ $advance->balance_amount <= 0 || $advance->advance_amount > 0 ? 'Paid' : 'Pay' }}
                            </button>
                            <a href="#" class="btn edit-button" id="updateSupplierAdvanceModal" data-id="{{ $advance->id }}" data-amount="{{ $advance->advance_amount }}" data-indent-id ="{{ $advance->indent_id }}"><i class="fa fa-edit" style="font-size:8px;color:brown"></i></a>
                            <button type="button" class="btn delete-button" id="deleteSupplierAdvanceModal" data-id="{{ $advance->id }}" data-amount="{{ $advance->advance_amount }}" data-indent-id ="{{ $advance->indent_id }}">
                                <i class="fa fa-trash" style="font-size:8px;color:red" aria-hidden="true"></i>
                            </button>

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
                        @php
                            $advanceAmount = $drivers->indent->indentRate->where('is_confirmed_rate', '1')->first();
                            $totalAdvanceAmout = ($advanceAmount) ? $advanceAmount->rate : '0.00';
                           
                        @endphp
                        @if($drivers->indent->supplierAdvances->isNotEmpty())
                    <tr>
                        <th id="supplier-balance-amount">Balance Amount -
                            {{ $totalAdvanceAmout - $drivers->indent->supplierAdvances->sum('advance_amount') }}
                        </th>
                    </tr>
                    @else
                    <tr>
                        <th>Balance Amount -
                            @if($drivers->indent->indentRate->isNotEmpty())
                                @php
                                    $rateAmount = $drivers->indent->indentRate->where('is_confirmed_rate', '1')->first();
                                @endphp
                            {{ ($rateAmount) ? $rateAmount->rate : '0.00' }}
                            @else
                            N/A
                            @endif
                    </tr>
                    @endif

                    </tr>
                    <!-- <tr>
                        @if($drivers->indent->supplierAdvances->isNotEmpty())
                    <tr>
                        <th>Balance Amount -
                            {{ optional($drivers->indent->indentRate->last())->rate - $drivers->indent->supplierAdvances->sum('advance_amount') }}
                        </th>
                    </tr>
                    @else
                    <tr>
                        <th>Balance Amount -
                            @if($drivers->indent->indentRate->isNotEmpty())
                            {{ $drivers->indent->indentRate->sortBy('rate')->last()->rate }}
                            @else
                            N/A
                            @endif
                    </tr> 
                    @endif

                    </tr>-->
                </table>

                <p class="fw-bolder">CUSTOMER PAYMENTS
                    <a href="{{ route('customer_advances.create', ['id' => optional($drivers->indent)->id]) }}" class="text-decoration-none text-light btn btn-primary btn-sm" style=" padding: 1px 5px;font-size:12px;">Pay</a>
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
                        <td>{{ ($payment->payment_type) ? $payment->payment_type : 'N/A' }}</td>
                        <td><span>&#8377;</span>{{ $payment->advance_amount }}</td>
                        <td>
                            <button type="button" class="text-decoration-none text-light btn btn-{{ $payment->balance_amount <= 0 || $payment->advance_amount > 0 ? 'secondary' : 'primary' }} btn-sm" style="padding: 1px 5px; font-size: 12px;" data-toggle="modal" data-target="#customerAdvanceModal{{ $drivers->indent->id }}" {{ $payment->balance_amount <= 0 || $payment->advance_amount > 0 ? 'disabled' : '' }}>
                                {{ $payment->balance_amount <= 0 || $payment->advance_amount > 0 ? 'Paid' : 'Pay' }}
                            </button>
                            <a href="#" class="btn edit-button" id="updateCustomerAdvanceModal" data-id="{{ $payment->id }}" data-amount="{{ $payment->advance_amount }}" data-indent-id ="{{ $drivers->indent->id }}"><i class="fa fa-edit" style="font-size:8px;color:brown"></i></a>
                            <button type="button" class="btn delete-button" id="deleteCustomerAdvanceModal" data-id="{{ $payment->id }}" data-amount="{{ $payment->advance_amount }}" data-indent-id ="{{ $drivers->indent->id }}">
                                <i class="fa fa-trash" style="font-size:8px;color:red" aria-hidden="true"></i>
                            </button>
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
                            echo number_format($balanceAmount, 2, '.', ''); 
                            @endphp
                        </th>
                    </tr>
                </table>
                <div>
                    <b>Profit Amount : </b>
                    @if($drivers->indent->customerRate && $drivers->indent->indentRate->first())
                    @php 
                        $supplierPrice = $drivers->indent->indentRate->where('is_confirmed_rate', '1')->first();
                        $supplierPriceAmount = ($supplierPrice) ? $supplierPrice->rate : '0.00';
                        $customerPrice = $drivers->indent->customerRate->rate;  
                        $profitAmount = $customerPrice - $supplierPriceAmount;
                    @endphp
                    <b> {{ $profitAmount }} </b>
                    @else
                    N/A
                    @endif
                </div>
                <br>

                <p class="fw-bolder">EXTRA COST DETAILS</p>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-info">Extra Cost Type</th>
                        <th class="bg-info">Amount</th>
                        <th class="bg-info">Bill Copy</th>
                        <th class="bg-info">Unloading Photo</th>
                        <th class="bg-info">Bill Copies</th>
                        <th class="bg-info">Action</th>
                    </tr>
                    <tr>
                        @if($extraCostDetails && $extraCostDetails->extra_cost_type != 'None')
                            <td>{{ ($extraCostDetails) ? $extraCostDetails->extra_cost_type : 'N/A' }}</td>
                            <td>{{ ($extraCostDetails) ? $extraCostDetails->amount : 'N/A' }}</td>
                            <td>
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
                            </td>
                            <td>
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
                            </td>
                            <td>
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
                            </td>
                            <td>
                                @if($extraCostDetails->is_confirmed == 0)
                                    <a class="btn" href="#" onclick="confirmExtraCost('{{ $extraCostDetails->id }}')"><i class="fa fa-check-circle" style="font-size:8px;color:green"></i></a>
                                @endif
                                <a href="/extra_costs/{{ $extraCostDetails->id }}/edit" class="btn edit-button"><i class="fa fa-edit" style="font-size:8px;color:brown"></i></a>
                                <button type="button" class="btn delete-button" id="deleteExtraCostModal" data-id="{{ $extraCostDetails->id }}" >
                                    <i class="fa fa-trash" style="font-size:8px;color:red" aria-hidden="true"></i>
                                </button>
                            </td>
                        @else
                            <td> Details Not Found</td>
                        @endif
                    </tr>
                    
                    
                </table>
                <br>
            @if($drivers->indent->trip_status == 0)
               <a href="#" onclick="confirmToUnloading('{{ $drivers->indent->id }}'); return false;" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Move To Unloading</a>
            @elseif($drivers->indent->trip_status == 1)
                @if($extraCostDetails)
                    <a href="#" onclick="confirmToPod('{{ $drivers->indent->id }}'); return false;" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Move To POD</a>
                @else 
                   <a href="/extracost/create/{{$drivers->indent->id}}" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Move To POD</a> 
                @endif
            @else
                @if($podDetails)
                    <a href="#" onclick="confirmToComplete('{{ $drivers->indent->id }}'); return false;" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Move To Complete</a>
                @else
                    <a href="/pods/create/{{$drivers->indent->id}}" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Move To POD</a>
                @endif
                
            @endif
            </div>
        </div>
</body>

<!-- Modal -->
        <div class="modal fade" id="supplierAdvanceModal" tabindex="-1" role="dialog" aria-labelledby="supplierAdvanceModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="rateModalLabel">Supplier Advance Amount</h5>
              </div>
              <div class="modal-body">
                <form method="POST">
                  @csrf

                  <label for="rate">Advance Amount:</label>
                  <input type="number" name="supplier_advance_amount" id="supplier_advance_amount" value="" class="form-control" required>

                  <input type="hidden" name="advance_id" id="advance_id" value="">
                  <input type="hidden" name="indent_id" id="indent_id" value="">
                  @error('rate')
                  <p style="color: red;">{{ $message }}</p>
                  @enderror
                  <br>
                  <button type="button" onclick="upadteSupplierAdvance();"class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="customerAdvanceModal" tabindex="-1" role="dialog" aria-labelledby="customerAdvanceModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="rateModalLabel">Customer Advance Amount</h5>
              </div>
              <div class="modal-body">
                <form method="POST">
                  @csrf

                  <label for="rate">Advance Amount:</label>
                  <input type="number" name="customer_advance_amount" id="customer_advance_amount" value="" class="form-control" required>

                  <input type="hidden" name="customer_advance_id" id="customer_advance_id" value="">
                  <input type="hidden" name="customer_indent_id" id="customer_indent_id" value="">
                  @error('rate')
                  <p style="color: red;">{{ $message }}</p>
                  @enderror
                  <br>
                  <button type="button" onclick="upadteCustomerAdvance();"class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="customerPriceModal" tabindex="-1" role="dialog" aria-labelledby="customerPriceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rateModalLabel">Customer Amount</h5>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                          @csrf
                            <label for="rate">Amount:</label>
                            <input type="number" name="customer_amount" id="customer_amount" value="" class="form-control" required>
                            <input type="hidden" name="customer_rate_id" id="customer_rate_id" value="">
                            <input type="hidden" name="customer_indent_id" id="customer_indent_id" value="">
                            <br>
                            <button type="button" onclick="upadteCustomerPrice();"class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="supplierPriceModal" tabindex="-1" role="dialog" aria-labelledby="supplierPriceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rateModalLabel">Supplier Amount</h5>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                          @csrf
                            <label for="rate">Amount:</label>
                            <input type="number" name="supplier_amount" id="supplier_amount" value="" class="form-control" required>
                            <input type="hidden" name="supplier_rate_id" id="supplier_rate_id" value="">
                            <input type="hidden" name="supplier_indent_id" id="supplier_indent_id" value="">
                            <br>
                            <button type="button" onclick="upadteSupplierPrice();"class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="confirmedDateModal" tabindex="-1" role="dialog" aria-labelledby="confirmedDateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rateModalLabel">Confirmed Date</h5>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                          @csrf
                            <label for="rate">Confirmed Date:</label>
                            <input type="date" name="confirmed_date" id="confirmed_date" value="" class="form-control" required>
                            <input type="hidden" name="indent_id" id="indent_id" value="">
                            <br>
                            <button type="button" onclick="updateConfirmedDate();"class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function handlePayment(button) {
        button.disabled = true;
    }

    function confirmToUnloading(indentId, amountId) {
        //axios.post(`/confirm-to-trips/${indentId}`)
        axios.get(`/move-to-unloading/${indentId}`)

            .then(response => {
                //sreturn false;
               // window.location.href = '/confirmed-locations';
                window.location.reload();
            })
            .catch(error => {
              // Handle error, e.g., show an error message
              console.error(error);
            });
      
    }

    function saveTrackingLink(indentId) {
        var trackingLink = $('#tracking_link').val();

        if(trackingLink) {
            $.ajax({
                url: "{{ route('trucks.tracking') }}",
                method: 'POST',
                data: {
                    indentId: indentId,
                    trackingLink: trackingLink,
                    _token: '{{ csrf_token() }}',
                },
                success: function(result) {
                    if(result.success == true) {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        } else {
            alert('Please Enter Tracking Link');
        }
    }

    function confirmExtraCost(extraCostId) {
        var isConfirmed = confirm("Are you sure you want to confirm this cost?");
        if (isConfirmed) {
            axios.get(`/confirm-extra-cost/${extraCostId}`)

            .then(response => {
                //sreturn false;
               // window.location.href = '/confirmed-locations';
                window.location.reload();
            })
            .catch(error => {
              // Handle error, e.g., show an error message
              console.error(error);
            });
        }
    }

    function confirmToPod(indentId) {
        var isCostConfirmed = $('#is_extra_cost_confirmed').val();
        var isExtraCostDetails = $('#is_extra_cost_details').val();
        var extra_cost_type = $('#extra_cost_type').val();

        if ((isExtraCostDetails == 1 && isCostConfirmed == 1) || (isExtraCostDetails == 1 && extra_cost_type == 'None' && isCostConfirmed == 0) || (isExtraCostDetails == 0 && isCostConfirmed == 0)) {
            var isConfirmed = confirm("Are you sure you want to confirm to move POD?");
            if (isConfirmed) {
                axios.get(`/move-to-pod/${indentId}`)

                .then(response => {
                    return false;
                   // window.location.href = '/confirmed-locations';
                    window.location.reload();
                })
                .catch(error => {
                  // Handle error, e.g., show an error message
                  console.error(error);
                });
            }
        } else {
            alert('Kindly Confirm Extra Cost Details');
        }
    }

    $(document).on('click', '#updateSupplierAdvanceModal', function() {
        var id = $(this).data('id');
        var amount = $(this).data('amount');
        var indentId = $(this).data('indent-id');

        $('#advance_id').val(id);
        $('#indent_id').val(indentId);
        $('#supplier_advance_amount').val(amount);
        $('#supplierAdvanceModal').modal('show');
    });

    function upadteSupplierAdvance() {
        var advanceId = $('#advance_id').val();
        var indentId = $('#indent_id').val();
        var advanceAmount = $('#supplier_advance_amount').val();

        $.ajax({
            url: "{{ route('supplier_advances.update-advance-amount') }}",
            method: 'POST',
            data: {
                indent_id: indentId,
                advanceId: advanceId,
                advance_amount: advanceAmount,
                _token: '{{ csrf_token() }}',
            },
            success: function(result) {
                $('#supplierAdvanceModal').modal('hide');
                if(result.success == true) {
                    window.location.reload();
                }
            },
            error: function(error) {
                console.error(error.responseText);
            }
        });
    }

    $(document).on('click', '#deleteSupplierAdvanceModal', function() {
        var isConfirmed = confirm("Are you sure you want to delete this?");

        if (isConfirmed) {
            var id = $(this).data('id');
            var amount = $(this).data('amount');
            var indentId = $(this).data('indent-id');

            $.ajax({
                url: "{{ route('supplier_advances.delete-advance-amount') }}",
                method: 'POST',
                data: {
                    indent_id: indentId,
                    advanceId: id,
                    advance_amount: amount,  // Ensure the variable name matches
                    _token: '{{ csrf_token() }}',
                },
                success: function(result) {
                    if (result.success == true) {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        }
    });

    $(document).on('click', '#updateCustomerAdvanceModal', function() {
        var id = $(this).data('id');
        var amount = $(this).data('amount');
        var indentId = $(this).data('indent-id');

        $('#customer_advance_id').val(id);
        $('#customer_indent_id').val(indentId);
        $('#customer_advance_amount').val(amount);
        $('#customerAdvanceModal').modal('show');
    });

    function upadteCustomerAdvance() {
        var advanceId = $('#customer_advance_id').val();
        var indentId = $('#customer_indent_id').val();
        var advanceAmount = $('#customer_advance_amount').val();

        $.ajax({
            url: "{{ route('customer_advances.update-advance-amount') }}",
            method: 'POST',
            data: {
                indent_id: indentId,
                advanceId: advanceId,
                advance_amount: advanceAmount,
                _token: '{{ csrf_token() }}',
            },
            success: function(result) {
                $('#supplierAdvanceModal').modal('hide');
                if(result.success == true) {
                    window.location.reload();
                }
            },
            error: function(error) {
                console.error(error.responseText);
            }
        });
    }

    $(document).on('click', '#deleteCustomerAdvanceModal', function() {
        alert();
        var isConfirmed = confirm("Are you sure you want to delete this?");

        if (isConfirmed) {
            var id = $(this).data('id');
            var amount = $(this).data('amount');
            var indentId = $(this).data('indent-id');

            $.ajax({
                url: "{{ route('customer_advances.delete-advance-amount') }}",
                method: 'POST',
                data: {
                    indent_id: indentId,
                    advanceId: id,
                    advance_amount: amount,  // Ensure the variable name matches
                    _token: '{{ csrf_token() }}',
                },
                success: function(result) {
                    if (result.success == true) {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        }
    });

    $(document).on('click', '#updateCustomerAmountModal', function() {
        var id = $(this).data('id');
        var amount = $(this).data('amount');
        var indentId = $(this).data('indent-id');

        $('#customer_rate_id').val(id);
        $('#customer_indent_id').val(indentId);
        $('#customer_amount').val(amount);
        $('#customerPriceModal').modal('show');
    });

    function upadteCustomerPrice() {
        var advanceId = $('#customer_rate_id').val();
        var indentId = $('#customer_indent_id').val();
        var customerAmount = $('#customer_amount').val();

        $.ajax({
            url: "{{ route('customer_advances.update-amount') }}",
            method: 'POST',
            data: {
                indent_id: indentId,
                id: advanceId,
                amount: customerAmount,
                _token: '{{ csrf_token() }}',
            },
            success: function(result) {
                $('#customerPriceModal').modal('hide');
                if(result.success == true) {
                    window.location.reload();
                }
            },
            error: function(error) {
                console.error(error.responseText);
            }
        });
    }
    /* */

    /* Supplier Price Update */
        $(document).on('click', '#updateSupplierAmountModal', function() {
            var id = $(this).data('id');
            var amount = $(this).data('amount');
            var indentId = $(this).data('indent-id');

            $('#supplier_rate_id').val(id);
            $('#supplier_indent_id').val(indentId);
            $('#supplier_amount').val(amount);
            $('#supplierPriceModal').modal('show');
        });

        function upadteSupplierPrice() {
            var advanceId = $('#supplier_rate_id').val();
            var indentId = $('#supplier_indent_id').val();
            var supplierAmount = $('#supplier_amount').val();

            $.ajax({
                url: "{{ route('supplier_advances.update-supplier-amount') }}",
                method: 'POST',
                data: {
                    indent_id: indentId,
                    id: advanceId,
                    amount: supplierAmount,
                    _token: '{{ csrf_token() }}',
                },
                success: function(result) {
                    $('#supplierPriceModal').modal('hide');
                    if(result.success == true) {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        }
    /* Supplier Price Update */
    
    function confirmToComplete(indentId, amountId) {
        var balanceAmount = $('#supplier-balance-amount').text();
       // alert(balanceAmount); return false;
        //axios.post(`/confirm-to-trips/${indentId}`)
        axios.post(`/complete/${indentId}`)

            .then(response => {
                //sreturn false;
               // window.location.href = '/confirmed-locations';
                window.location.reload();
            })
            .catch(error => {
              // Handle error, e.g., show an error message
              console.error(error);
            });
      
    }
    
    $(document).on('click', '#deleteExtraCostModal', function() {
        var isConfirmed = confirm("Are you sure you want to delete this?");

        if (isConfirmed) {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('delete-extra-cost') }}",
                method: 'GET',
                data: {
                    ExtraCostId : id,
                },
                success: function(result) {
                    if (result.success == true) {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        }
    });
    
    /* Confirmed Date Update */
        $(document).on('click', '#updateConfirmedDateModal', function() {
            var confirmedDate = $(this).data('confirmed-date');
            var indentId = $(this).data('indent-id');

            $('#confirmed_date').val(confirmedDate);
            $('#indent_id').val(indentId);
            $('#confirmedDateModal').modal('show');
        });

        function updateConfirmedDate() {
            var indentId = $('#indent_id').val();
            var confirmed_date = $('#confirmed_date').val();

            $.ajax({
                url: "{{ route('indent.update-confirmed-date') }}",
                method: 'POST',
                data: {
                    indent_id: indentId,
                    confirmed_date: confirmed_date,
                    _token: '{{ csrf_token() }}',
                },
                success: function(result) {
                    $('#confirmedDateModal').modal('hide');
                    if(result.success == true) {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        }
    /* Supplier Price Update */
    
</script>

</html>