@extends('layouts.sidebar')

@section('content')
<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col">
            <div class="d-flex">
                {{-- <div class="me-3">@include('customers.edit')</div>
                <span>@include('customers.delete')</span> --}}
                <button type="button" class="btn dash1"  style="margin-left:600px">
                <a href="{{ route('customers.index') }}" class="text-decoration-none text-dark"> Back To Customer</a>
            </button>
            </div>
        </div>
        <div class="col-lg-12 mt-5" style="background-color:#D9D9D9">
            <ul class="list-unstyled">
                <li class="row">
                    <strong class="col-sm-3">Name</strong>
                    <span class="col-sm-7">{{ $customer->customer_name}}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Company Name</strong>
                    <span class="col-sm-7">{{ $customer->company_name}}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Address</strong>
                    <span class="col-sm-7">{{ $customer->address}}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Contact Number</strong>
                    <span class="col-sm-7">{{ $customer->contact_number }}</span>
                </li>
                {{-- <li class="row">
                    <strong class="col-sm-3">GST Number</strong>
                    <span class="col-sm-7">{{ $customer->gst_number }}</span>
                </li> --}}
                <li class="row">
                    <strong class="col-sm-3">Lead Source</strong>
                    <span class="col-sm-7">{{ $customer->lead_source }}</span>
                </li>
                <li class="row">
                    <strong class="col-sm-3">Business Card</strong>
                    <span class="col-sm-7">
                        @if($customer->business_card)
                        @foreach(json_decode($customer->business_card) as $index => $path)
                        <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-decoration-underline">
                            Business Card {{ $index + 1 }}
                        </a><br>
                        @endforeach
                        @else
                        <p>No business card images uploaded.</p>
                        @endif
                    </span>
                </li>

                <li class="row">
                    <strong class="col-sm-3">GST Document</strong>
                    <span class="col-sm-7">
                        @if($customer->gst_document)
                        <a href="{{ asset('storage/' . $customer->gst_document) }}" target="_blank" class="text-decoration-underline">
                            GST Document
                        </a>
                        @else
                        No GST Document Uploaded
                        @endif
                    </span>
                </li>

                <li class="row">
                    <strong class="col-sm-3">Company Name Board</strong>
                    <span class="col-sm-7">
                        @if($customer->company_name_board)
                        <a href="{{ asset('storage/' . $customer->company_name_board) }}" target="_blank" class="text-decoration-underline">
                            Company Name Board
                        </a>
                        @else
                        No Company Name Board Uploaded
                        @endif
                    </span>
                </li>


            </ul>
        </div>
    </div>
</div>
@endsection