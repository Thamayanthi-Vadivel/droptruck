@extends('layouts.sidebar')

@section('content')
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col">
            <div class="d-flex">
                {{-- <div class="me-3">@include('suppliers.edit')</div> --}}
                
            </div>
        </div>
        <div class="col-auto">
            <button type="button" class="btn dash1">
                <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-dark">Back To Supplier</a>
            </button>
        </div>
        <div class="col-lg-12 mt-5" style="background-color:#D9D9D9">
            <ul class="list-unstyled">
                <li class="row">
                    <strong class="col-sm-3">Vendor Name:</strong>
                    <span class="col-sm-7">{{ $supplier->supplier_name }}</span>

                </li>
                <li class="row">
                    <strong class="col-sm-3">Vendor Type</strong>
                    <span class="col-sm-7">{{ $supplier->supplier_type }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Company Name:</strong>
                    <span class="col-sm-7">{{ $supplier->company_name}}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Contact Number</strong>
                    <span class="col-sm-7">{{ $supplier->contact_number }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Account Number</strong>
                    <span class="col-sm-7">{{ $supplier->account_number }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">IFSC Code</strong>
                    <span class="col-sm-7">{{ $supplier->ifsc_code }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Pancard Number</strong>
                    <span class="col-sm-7">{{ $supplier->pan_card_number }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Pancard:</strong>
                    <span class="col-sm-7">
                        @if($supplier->pan_card)
                        <a href="{{ asset($supplier->pan_card) }}" target="_blank" class="text-decoration-underline">View PAN Card</a>
                        @else
                        No PAN Card uploaded
                        @endif
                    </span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Business Card:</strong>
                    <span class="col-sm-7">
                        @if($supplier->business_card)
                        @foreach(json_decode($supplier->business_card) as $index => $path)
                        <a href="{{asset($path) }}" target="_blank" class="text-decoration-underline">
                            Business Card {{ $index + 1 }}<br>
                            @endforeach
                        </a>
                        @else
                        No Business Card uploaded
                        @endif
                    </span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Others:</strong>
                    <span class="col-sm-7">
                        @if($supplier->memo)
                        <a href="{{ asset($supplier->memo) }}" target="_blank" class="text-decoration-underline">View Others Document</a>
                        @else
                        No Memo uploaded
                        @endif
                    </span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Bank Details:</strong>
                    <span class="col-sm-7">
                        @if($supplier->bank_details)
                        <a href="{{asset($supplier->bank_details) }}" target="_blank" class="text-decoration-underline">
                            Bank Details<br>
                        </a>
                        @else
                        No Bank Details uploaded
                        @endif
                    </span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Eway Bill:</strong>
                    <span class="col-sm-7">
                        @if($supplier->eway_bill)
                        <a href="{{ asset($supplier->eway_bill) }}" target="_blank" class="text-decoration-underline">Eway Bill</a>
                        @else
                        No Eway Bill uploaded
                        @endif
                    </span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Trips Invoices:</strong>
                    <span class="col-sm-7">
                        @if($supplier->trips_invoices)
                        <a href="{{ asset($supplier->trips_invoices) }}" target="_blank" class="text-decoration-underline">Trips Invoices</a>
                        @else
                        No Trips Invoices uploaded
                        @endif
                    </span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Others Document:</strong>
                    <span class="col-sm-7">
                        @if($supplier->other_document)
                        <a href="{{ asset($supplier->other_document) }}" target="_blank" class="text-decoration-underline">Others Document</a>
                        @else
                        No Other Documents uploaded
                        @endif
                    </span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Remarks:</strong>
                    <span class="col-sm-7">{{ $supplier->remarks }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>


@endsection