@extends('layouts.sidebar')

@section('content')
<style type="text/css">
    .section {
            width: 50%; /* Each section takes half of the width */
            float: left; /* Float the sections to make them appear side by side */
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
            padding: 20px; /* Add some padding for spacing */
        }
</style>
<div>
            <h2 class="btn btn-primary text-white fw-bolder float-end mt-1">User : {{ auth()->user()->name }}</h2>
        </div>
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col">
            <div class="d-flex">
                <button type="button" class="btn dash1"  style="margin-left:600px">
                @if(auth()->user()->role_id == 4 || auth()->user()->role_id == 1 ||auth()->user()->role_id == 2)
                <a href="{{ route('loading') }}" class="text-decoration-none text-dark"> Back</a>
                @else
                <a href="/trips" class="text-decoration-none text-dark"> Back</a>
                @endif
            </button>
            </div>
        </div>
   
        <div class="col-lg-12 mt-5" style="background-color:#D9D9D9">
                <div class="section">
                    <h3>Enquiry Details</h3>
                    <ul class="list-unstyled">
                        <li class="row">
                            <strong class="col-sm-3">Enquiry No</strong>
                            <span class="col-sm-7">{{ $indent->getUniqueENQNumber() }}</span>
                        </li>
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                        <li class="row">
                            <strong class="col-sm-3">Customer Name</strong>
                            <span class="col-sm-7">{{ $indent->customer_name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Company Name</strong>
                            <span class="col-sm-7">
                                {{ $indent->company_name }}
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Customer Number</strong>
                            <span class="col-sm-7">
                                {{ $indent->number_1 }}
                            </span>
                        </li>
                        @endif
                        <li class="row">
                            <strong class="col-sm-3">Pickup Location</strong>
                            <span class="col-sm-7">{{ $indent->pickup_location_id }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Drop Location</strong>
                            <span class="col-sm-7">{{ $indent->drop_location_id }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Material Type</strong>
                            <span class="col-sm-7">{{ ($indent->materialType) ? $indent->materialType->name : 'N/A' }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Truck Type</strong>
                            <span class="col-sm-7">{{ $indent->truckType->name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Body Type</strong>
                            <span class="col-sm-7">{{ $indent->body_type }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Weight</strong>
                            <span class="col-sm-7">{{ $indent->weight }} {{ $indent->weight_unit }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Driver Rate</strong>
                            <span class="col-sm-7">{{ $indent->driver_rate }}</span>
                        </li>
                        @if(auth()->user()->role_id != 4)
                        <li class="row">
                            <strong class="col-sm-3">Customer Rate</strong>
                            <span class="col-sm-7">{{ $indent->customerRate->rate }}</span>
                        </li>
                        @endif
                        <li class="row">
                            <strong class="col-sm-3">Sales Person</strong>
                            <span class="col-sm-7">{{ $indent->user->name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Supplier Name</strong>
                            <span class="col-sm-7">{{ $supplierName }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Created Date</strong>
                            <span class="col-sm-7">{{ $indent->created_at }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Remarks</strong>
                            <span class="col-sm-7">{{ ($indent->remarks) ? $indent->remarks : 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
                
                <div class="section">
                    <h3>Driver Information</h3>
                    <ul class="list-unstyled">
                        <li class="row">
                            <strong class="col-sm-3">Driver Name</strong>
                            <span class="col-sm-7">{{ $driver->driver_name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Driver Number</strong>
                            <span class="col-sm-7">{{ $driver->driver_number }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Driver Rate</strong>
                            <span class="col-sm-7">
                                {{ $driverAmount->rate }}
                            </span>
                        </li>
                        @if(auth()->user()->role_id !== 4)
                        <li class="row">
                            <strong class="col-sm-3">Customer Rate</strong>
                            <span class="col-sm-7">
                                {{ $customerAmount->rate }}
                            </span>
                        </li>
                        @endif
                        <li class="row">
                            <strong class="col-sm-3">Driver Advance Amount</strong>
                            <span class="col-sm-7">
                                {{ ($supplierAdvanceAmount) ? $supplierAdvanceAmount : 'N/A' }}
                            </span>
                        </li>
                        @if(auth()->user()->role_id != 4)
                        <li class="row">
                            <strong class="col-sm-3">Customer Advance Amount</strong>
                            <span class="col-sm-7">
                                {{ ($customerAdvanceAmount) ? $customerAdvanceAmount : 'N/A' }}
                            </span>
                        </li>
                        @endif
                        <li class="row">
                            <strong class="col-sm-3">Vehicle Number</strong>
                            <span class="col-sm-7">{{ $driver->vehicle_number }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Vehicle Photo</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('/' . $driver->vehicle_photo) }}" target="_blank" class="text-decoration-underline">
                                    Vehicle Photo
                                </a>
                                <a href="{{ asset('/' . $driver->vehicle_photo) }}" download="{{ asset('/' . $driver->vehicle_photo) }}">
                                    <i class="fas fa-download"></i>
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Driver License</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('/' . $driver->driver_license) }}" target="_blank" class="text-decoration-underline">
                                    Driver License
                                </a>
                                <a href="{{ asset('/' . $driver->driver_license) }}" download="{{ asset('/' . $driver->driver_license) }}">
                                    <i class="fas fa-download"></i>
                                </a>
                                <br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">RC Book</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('/' . $driver->rc_book) }}" target="_blank" class="text-decoration-underline">
                                    RC Book
                                </a>
                                <a href="{{ asset('/' . $driver->rc_book) }}" download="{{ asset('/' . $driver->rc_book) }}">
                                    <i class="fas fa-download"></i>
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Insurance</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('/' . $driver->insurance) }}" target="_blank" class="text-decoration-underline">
                                    Insurance
                                </a>
                                <a href="{{ asset('/' . $driver->insurance) }}" download="{{ asset('/' . $driver->insurance) }}">
                                    <i class="fas fa-download"></i>
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Tracking Link</strong>
                            <span class="col-sm-7">
                                @if($indent->tracking_link)
                                    <a href="{{ $indent->tracking_link }}" target="_blank" class="text-decoration-underline">
                                        {{ ($indent->tracking_link) ? $indent->tracking_link : 'N/A' }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Extra Cost Type</strong>
                            <span class="col-sm-7">
                                {{ ($extraCostType) ? $extraCostType->extra_cost_type : 'N/A' }}
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Extra Cost Amount</strong>
                            <span class="col-sm-7">
                                {{ ($extraCostAmount) ? $extraCostAmount : 'N/A' }}
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Courier Number</strong>
                            <span class="col-sm-7">
                                {{ ($podDetails) ? $podDetails->courier_receipt_no : 'N/A' }}
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Courier Details</strong>
                            @if($podDetails)
                                <span class="col-sm-7">
                                    <a href="{{ asset('/' . $podDetails->pod_courier) }}" target="_blank" class="text-decoration-underline">
                                        Courier
                                    </a>
                                    <a href="{{ asset('/' . $podDetails->pod_courier) }}" download="{{ asset('/' . $podDetails->pod_courier) }}">
                                        <i class="fas fa-download"></i>
                                    </a><br>
                                </span>
                            @else
                                N/A
                            @endif
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">POD Soft Copy</strong>
                            @if($podDetails)
                                <span class="col-sm-7">
                                    <a href="{{ asset('/' . $podDetails->pod_soft_copy) }}" target="_blank" class="text-decoration-underline">
                                        POD Soft Copy
                                    </a>
                                    <a href="{{ asset('/' . $podDetails->pod_soft_copy) }}" download="{{ asset('/' . $podDetails->pod_soft_copy) }}">
                                        <i class="fas fa-download"></i>
                                    </a><br>
                                </span>
                            @else
                                N/A
                            @endif
                        </li>
                    </ul>
                </div>
               @if($suppliers)
                <div class="section">
                    <h3>Vendor Information</h3>
                    <ul class="list-unstyled" style="margin: 0; padding: 0;">
                        <li class="row">
                            <strong class="col-sm-3">Vendor Name</strong>
                            <span class="col-sm-7">{{ $suppliers->supplier_name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Vendor Number</strong>
                            <span class="col-sm-7">{{ $suppliers->contact_number }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Vendor Type</strong>
                            <span class="col-sm-7">{{ $suppliers->supplier_type }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Company Name</strong>
                            <span class="col-sm-7">{{ $suppliers->company_name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Bank Name</strong>
                            <span class="col-sm-7">{{ $suppliers->bank_name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">IFSC Code</strong>
                            <span class="col-sm-7">{{ $suppliers->ifsc_code }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Account Number</strong>
                            <span class="col-sm-7">{{ $suppliers->account_number }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Pan Card Number</strong>
                            <span class="col-sm-7">{{ $suppliers->pan_card_number }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Pancard</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('/' . $suppliers->pan_card) }}" target="_blank" class="text-decoration-underline">
                                    Pancard
                                </a>
                                <a href="{{ asset('/' . $suppliers->pan_card) }}" download="{{ asset('/' . $suppliers->pan_card) }}">
                                    <i class="fas fa-download"></i>
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Bank Details</strong>
                            <span class="col-sm-7">
                                @php
                                    $bankDetails = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->bank_details);
                                @endphp
                                @if($suppliers->bank_details) 
                                    <a href="{{ asset('/' . $bankDetails) }}" target="_blank" class="text-decoration-underline">
                                        Bank Details
                                    </a>
                                    <a href="{{ asset('/' . $bankDetails) }}" download="{{ asset('/' . $bankDetails) }}">
                                        <i class="fas fa-download"></i>
                                    </a><br>
                                @endif
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Business Card</strong>
                            <span class="col-sm-7">
                                <!--Added by Luqman-->
                                @php
                                    $businessCardURL = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->business_card);
                                @endphp
                                <a href="{{ asset('/' . $businessCardURL) }}" target="_blank" class="text-decoration-underline">
                                    Business Card
                                </a>
                                <a href="{{ asset('/' . $businessCardURL) }}" download="{{ asset('/' . $businessCardURL) }}">
                                    <i class="fas fa-download"></i>
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Others</strong>
                            <span class="col-sm-7">
                                @php
                                    $others = preg_replace('/[^a-zA-Z0-9\-\/.]/', '', $suppliers->memo);
                                @endphp
                                @if($suppliers->memo) 
                                    <a href="{{ asset('/' . $others) }}" target="_blank" class="text-decoration-underline">
                                        Others
                                    </a>
                                    <a href="{{ asset('/' . $others) }}" download="{{ asset('/' . $others) }}">
                                        <i class="fas fa-download"></i>
                                    </a><br>
                                @endif
                            </span>
                        </li>
                         <li class="row">
                            <strong class="col-sm-3">Tracking Link</strong>
                            <span class="col-sm-7">
                                <a href="" target="_blank" class="text-decoration-underline">
                                    Tracking
                                </a>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Driver Advcne paid</strong>
                            <span class="col-sm-7">
                            {{ is_object($suppliersAdvanceAmt) && $suppliersAdvanceAmt->advance_amount ? $suppliersAdvanceAmt->advance_amount : 0.00 }}
                            </span>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
       
    </div>
</div>

@endsection