<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Truck</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }
        .card-body {
            padding: 10px;
        }
        .label-width {
            width: 150px;
            display: inline-block;
        }
        .detail-row {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
        <div class="header">
            <h2>Trip Details</h2>
            <div class="text-center">
        <a href="{{ route('generate-invoice', ['id' => $indent->id]) }}" class="btn btn-primary" target="_blank">Download PDF</a>
    </div>
        </div>
        <div class="card">
            <div class="card-header">
                Full Trip Details
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <strong class="label-width">Enq Number:</strong>
                    <span>{{ $indent->getUniqueENQNumber() }}</span>
                </div>
                @if(auth()->user()->role_id != 4)
                    <div class="detail-row">
                        <strong class="label-width">Customer Name:</strong>
                        <span>{{ $indent->customer_name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong class="label-width">Company Name:</strong>
                        <span>{{ $indent->company_name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong class="label-width">Customer Number:</strong>
                        <span>{{ $indent->number_1 }}</span>
                    </div>
                @endif
                <div class="detail-row">
                    <strong class="label-width">Pickup Location:</strong>
                    <span>{{ $indent->pickup_location_id }}</span>
                </div>
                <div class="detail-row">
                    <strong class="label-width">Drop Location:</strong>
                    <span>{{ $indent->drop_location_id }}</span>
                </div>
                <div class="detail-row">
                    <strong class="label-width">Truck Type:</strong>
                    <span>{{ $indent->truckType ? $indent->truckType->name : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <strong class="label-width">Body Type:</strong>
                    <span>{{ $indent->body_type }}</span>
                </div>
                <div class="detail-row">
                    <strong class="label-width">Weight:</strong>
                    <span>{{ $indent->weight }}</span>
                </div>
                <div class="detail-row">
                    <strong class="label-width">Material Type:</strong>
                    <span>{{ $indent->materialType ? $indent->materialType->name : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <strong class="label-width">Driver Rate:</strong>
                    <span>{{ $indent->driver_rate }}</span>
                </div>
                @if(auth()->user()->role_id != 4)
                <div class="detail-row">
                    <strong class="label-width">Customer Rate:</strong>
                    <span>{{ $indent->customerRate->rate }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <strong class="label-width">Salesperson:</strong>
                    <span>{{ $indent->user->name }}</span>
                </div>
                <div class="detail-row">
                    <strong class="label-width">Supplier Name:</strong>
                    <span>{{ $supplierName }}</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                Driver Details
            </div>
            <div class="card-body">
                @foreach ($indent->driverDetails as $driverDetail)
                <div class="detail-row">
                    <strong>Driver Name:</strong>
                    <span>{{ $driverDetail->driver_name }}</span>
                </div>
                <div class="detail-row">
                    <strong>Driver Number:</strong>
                    <span>{{ $driverDetail->driver_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Vehicle Number:</strong>
                    <span>{{ $driverDetail->vehicle_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Vehicle Type:</strong>
                    <span>{{ $driverDetail->vehicle_type }}</span>
                </div>
                <div class="detail-row">
                    <strong>Driver Base Location:</strong>
                    <span>{{ $driverDetail->driver_base_location }}</span>
                </div>
                <div class="detail-row">
                    <strong>Vehicle Photo:</strong>
                    <span>
                        <a href="{{ asset('/' . $driverDetail->vehicle_photo) }}" target="_blank" class="text-decoration-underline">
                            Vehicle Photo
                        </a>
                        <a href="{{ asset('/' . $driverDetail->vehicle_photo) }}" download="{{ asset('/' . $driverDetail->vehicle_photo) }}">
                            <i class="fas fa-download"></i>
                        </a>
                    </span>
                </div>
                <div class="detail-row">
                    <strong>Vehicle Photo:</strong>
                    <span>
                        <a href="{{ asset('/' . $driverDetail->vehicle_photo) }}" target="_blank" class="text-decoration-underline">
                            Vehicle Photo
                        </a>
                        <a href="{{ asset('/' . $driverDetail->vehicle_photo) }}" download="{{ asset('/' . $driverDetail->vehicle_photo) }}">
                            <i class="fas fa-download"></i>
                        </a>
                    </span>
                </div>
                <div class="detail-row">
                    <strong>Driver License:</strong>
                    <span>
                        <a href="{{ asset('/' . $driverDetail->driver_license) }}" target="_blank" class="text-decoration-underline">
                            Driver License
                        </a>
                        <a href="{{ asset('/' . $driverDetail->driver_license) }}" download="{{ asset('/' . $driverDetail->driver_license) }}">
                            <i class="fas fa-download"></i>
                        </a>
                    </span>
                </div>
                <div class="detail-row">
                    <strong>RC Book:</strong>
                    <span>
                        <a href="{{ asset('/' . $driverDetail->rc_book) }}" target="_blank" class="text-decoration-underline">
                            RC Book
                        </a>
                        <a href="{{ asset('/' . $driverDetail->rc_book) }}" download="{{ asset('/' . $driverDetail->rc_book) }}">
                            <i class="fas fa-download"></i>
                        </a>
                    </span>
                </div>
                <div class="detail-row">
                    <strong>Insurance:</strong>
                    <span>
                        <a href="{{ asset('/' . $driverDetail->insurance) }}" target="_blank" class="text-decoration-underline">
                            Insurance
                        </a>
                        <a href="{{ asset('/' . $driverDetail->insurance) }}" download="{{ asset('/' . $driverDetail->insurance) }}">
                            <i class="fas fa-download"></i>
                        </a>
                    </span>
                </div>
                <div class="detail-row">
                    <strong>Tracking Link:</strong>
                    <span>
                        @if($indent->tracking_link)
                            <a href="{{ $indent->tracking_link }}" target="_blank" class="text-decoration-underline">
                                {{ ($indent->tracking_link) ? 'Link' : 'N/A' }}
                            </a>
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Customer & Supplier Advance Pending Status
            </div>
            <div class="card-body">
                @if(auth()->user()->role_id != 4)
                <div class="detail-row">
                    <strong>Customer Advances:</strong>
                    <span>{{ $indent->customerAdvances->sum('advance_amount') }}</span>
                </div>
                <div class="detail-row">
                    <strong>Customer Balance Amount:</strong>
                    <span>
                        @php
                            $totalAmount = optional($indent->customerRate)->rate;
                            $advanceSum = $indent->customerAdvances->sum('advance_amount');
                            $balanceAmount = $totalAmount - $advanceSum;
                            echo $balanceAmount;
                        @endphp
                    </span>
                </div>
                @endif
                <div class="detail-row">
                    <strong>Supplier Advances:</strong>
                    <span>{{ $indent->supplierAdvances->sum('advance_amount') }}</span>
                </div>
                <div class="detail-row">
                    <strong>Supplier Balance Amount:</strong>
                    <span>{{ optional($indent->indentRate->last())->rate - $indent->supplierAdvances->sum('advance_amount') }}</span>
                </div>
                <div class="detail-row">
                    <strong>Extra Cost Type:</strong>
                    <span>{{ ($extraCostType) ? $extraCostType->extra_cost_type : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <strong>Extra Cost Amount:</strong>
                    <span>{{ ($extraCostAmount) ? $extraCostAmount : 'N/A' }}</span>
                </div>
            </div>
        </div>

        @if($suppliers)
            <div class="card">
                <div class="card-header">
                    Vendor Information
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <strong>Vendor Name:</strong>
                        <span>{{ $suppliers->supplier_name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Vendor Number:</strong>
                        <span>{{ $suppliers->contact_number }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Vendor Type:</strong>
                        <span>{{ $suppliers->supplier_type }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Company Name:</strong>
                        <span>{{ $suppliers->company_name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Bank Name:</strong>
                        <span>{{ $suppliers->bank_name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>IFSC Code:</strong>
                        <span>{{ $suppliers->ifsc_code }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Account Number:</strong>
                        <span>{{ $suppliers->account_number }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Pan Card Number:</strong>
                        <span>{{ $suppliers->pan_card_number }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Pancard:</strong>
                        <span>
                            <a href="{{ asset('/' . $suppliers->pan_card) }}" target="_blank" class="text-decoration-underline">
                                Pancard
                            </a>
                            <a href="{{ asset('/' . $suppliers->pan_card) }}" download="{{ asset('/' . $suppliers->pan_card) }}">
                                <i class="fas fa-download"></i>
                            </a>
                        </span>
                    </div>
                    <div class="detail-row">
                        <strong>Bank Details:</strong>
                        <span>
                            @php
                                $bankDetails = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->bank_details);
                            @endphp
                            @if($suppliers->bank_details) 
                                <a href="{{ asset('/' . $bankDetails) }}" target="_blank" class="text-decoration-underline">
                                    Bank Details
                                </a>
                                <a href="{{ asset('/' . $bankDetails) }}" download="{{ asset('/' . $bankDetails) }}">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <strong>Business Card:</strong>
                        <span>
                            @php
                                $businessCardURL = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->business_card);
                            @endphp
                            <a href="{{ asset('/' . $businessCardURL) }}" target="_blank" class="text-decoration-underline">
                                Business Card
                            </a>
                            <a href="{{ asset('/' . $businessCardURL) }}" download="{{ asset('/' . $businessCardURL) }}">
                                <i class="fas fa-download"></i>
                            </a>
                        </span>
                    </div>
                    <div class="detail-row">
                        <strong>Others:</strong>
                        <span>
                            @php
                                    $others = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->memo);
                                @endphp
                                @if($suppliers->memo) 
                                    <a href="{{ asset('/' . $others) }}" target="_blank" class="text-decoration-underline">
                                        Others
                                    </a>
                                    <a href="{{ asset('/' . $others) }}" download="{{ asset('/' . $others) }}">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <strong>Trips Invoices:</strong>
                        <span>
                            @php
                                $trips_invoices = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->trips_invoices);
                            @endphp
                            @if($suppliers->trips_invoices) 
                                <a href="{{ asset('/' . $trips_invoices) }}" target="_blank" class="text-decoration-underline">
                                    Trips Invoices
                                </a>
                                <a href="{{ asset('/' . $trips_invoices) }}" download="{{ asset('/' . $trips_invoices) }}">
                                    <i class="fas fa-download"></i>
                                </a><br>
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <strong>Other Documents:</strong>
                        <span>
                           @php
                                $other_document = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->other_document);
                            @endphp
                            @if($suppliers->other_document) 
                                <a href="{{ asset('/' . $other_document) }}" target="_blank" class="text-decoration-underline">
                                    Other Documents
                                </a>
                                <a href="{{ asset('/' . $other_document) }}" download="{{ asset('/' . $other_document) }}">
                                    <i class="fas fa-download"></i>
                                </a><br>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
