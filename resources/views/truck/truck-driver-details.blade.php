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

<div class="main mb-4 mt-1">
    <div class="row align-items-center">
        <div class="col">
            <div class="d-flex">
                <button type="button" class="btn dash1"  style="margin-left:600px">
                <a href="{{ route('loading') }}" class="text-decoration-none text-dark"> Back</a>
            </button>
            </div>
        </div>
       
        <div class="col-lg-12 mt-5" style="background-color:#D9D9D9">
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
                            <strong class="col-sm-3">Vehicle Number</strong>
                            <span class="col-sm-7">{{ $driver->vehicle_number }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Vehicle Photo</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('storage/' . $driver->vehicle_photo) }}" target="_blank" class="text-decoration-underline">
                                    Vehicle Photo
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Driver License</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('storage/' . $driver->driver_license) }}" target="_blank" class="text-decoration-underline">
                                    Driver License
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">RC Book</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('storage/' . $driver->rc_book) }}" target="_blank" class="text-decoration-underline">
                                    RC Book
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Insurance</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('storage/' . $driver->insurance) }}" target="_blank" class="text-decoration-underline">
                                    Insurance
                                </a><br>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="section">
                    <h3>Supplier Information</h3>
                    <ul class="list-unstyled" style="margin: 0; padding: 0;">
                        <li class="row">
                            <strong class="col-sm-3">Supplier Name</strong>
                            <span class="col-sm-7">{{ $suppliers->supplier_name }}</span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Supplier Type</strong>
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
                                <a href="{{ asset('storage/' . $suppliers->pan_card) }}" target="_blank" class="text-decoration-underline">
                                    Pancard
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Bank Details</strong>
                            <span class="col-sm-7">
                                
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Business Card</strong>
                            <span class="col-sm-7">
                                <a href="{{ asset('storage/' . $suppliers->business_card) }}" target="_blank" class="text-decoration-underline">
                                    Business Card
                                </a><br>
                            </span>
                        </li>
                        <li class="row">
                            <strong class="col-sm-3">Others</strong>
                            <span class="col-sm-7">
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
                            0.00
                            </span>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
       
    </div>
</div>

    <div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="rateModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="rateModalLabel">Driver Advance Amount</h5>
              </div>
              <div class="modal-body">
                <form method="POST" action="">
                  @csrf

                  <label for="rate">Amount:</label>
                  <input type="number" name="rate" value="" required>

                  <input type="hidden" name="indent_id" value="">

                  
                  <p style="color: red;"></p>
                

                  <button type="submit" class="btn btn-primary">Submit Amount</button>
                </form>
              </div>
            </div>
          </div>
        </div>

<script>
    document.getElementById('openRateModal').addEventListener('click', function() {
      var myModal = new bootstrap.Modal(document.getElementById('rateModal'));
      myModal.show();
    });
  </script>
@endsection