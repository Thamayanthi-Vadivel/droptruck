

@extends('layouts.sidebar')
@section('content')

<!-- Display error message -->
@if(Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif
<div class="card mt-1">
    <div class="card-header" style="background-color: #F98917;">
        <h5 class="card-title text-white">Add Suppliers</h5>
    </div>
    @if (!is_null($indentId))
    <div>
        <!-- <button type="button" class="btn btn-danger m-1" style="font-size: 8px; padding: 5px 10px;" data-toggle="modal" data-target="#cancelModal">
            Cancel
        </button> -->
        <button type="button" class="btn btn-danger m-1" style="font-size: 8px; padding: 5px 10px;">
            <a href="/trips" class="btn btn-danger m-1"  style="font-size: 8px; padding: 5px 10px;"> Back</a>
        </button>
    </div>
    @endif


    <!-- Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('cancel-indents-by-locations') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pickup_location_id" value="{{ $pickupLocationId }}">
                    <input type="hidden" name="drop_location_id" value="{{ $dropLocationId }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">Select Reason for Cancellation:</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">Select Reason for Cancellation:</label>
                            <select class="form-control" id="reason" name="reason">
                                <option value="Not Responding">Not Responding</option>
                                <option value="Material not ready">Material not ready</option>
                                <option value="Duplicate Enquiry">Duplicate Enquiry</option>
                                <option value="Unavailability of vehicle">Unavailability of vehicle</option>
                                <option value="Trip Postponed">Trip Postponed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        
        {!! Form::open(['route' => 'suppliers.store', 'enctype' => 'multipart/form-data']) !!}
        @csrf
        <div class="form-group mb-3 float-end">
            @if ($indents instanceof \App\Models\Indent)
            {!! Form::hidden('indent_id', $indents->id) !!}
            <button type="button" class="btn btn-sm text-white btn-danger">{{ $indents->getUniqueENQNumber() }}</button>
            @endif
        </div>
        <div class="form-group mb-3 float-end" style="display: none;">
            {!! Form::label('supplier_id', 'Supplier Id') !!}
        {!! Form::hidden('supplier_id', null, ['class' => 'form-control']) !!}
        </div>
        
    <div class="form-group col-lg-4 mb-3">
        {!! Form::label('contact_number', 'Contact Number') !!}
        {!! Form::text('contact_number', null, ['class' => 'form-control']) !!}
        @error('contact_number')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="add-new-supplier">
        <div class="row  d-flex justify-content-center gap-2 mt-3">
            <div class="form-group mb-3 col-lg-3">
                {!! Form::label('supplier_name', 'Vendor Name') !!}
                {!! Form::text('supplier_name', null, ['class' => 'form-control']) !!}
                @error('supplier_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3 col-lg-3">
                {!! Form::label('supplier_type', 'Vendor Type') !!}
                {!! Form::select('supplier_type', [
                    'select' => 'Select',
                    'Broker or Transporter' => 'Broker or Transporter',
                    'Vehicle Owner' => 'Vehicle Owner',
                    'Owner Come Driver' => 'Owner Come Driver'
                ], 
                null, 
                ['class' => 'form-select form-select-sm', 'id' => 'vehicle_type', 'fdprocessedid' => 'hnprge']
            ) !!}
            </div>

            <div class="form-group mb-3 col-lg-3">
                {!! Form::label('company_name', 'Company Name') !!}
                {!! Form::text('company_name', null, ['class' => 'form-control']) !!}
                @error('company_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row gap-3  d-flex justify-content-center">
            <div class="form-group  col-lg-3 mb-3">
                {!! Form::label('bank_name', 'Bank Name') !!}
                {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}
                @error('bank_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group  col-lg-3 mb-3">
                {!! Form::label('ifsc_code', 'IFSC Code') !!}
                {!! Form::text('ifsc_code', null, ['class' => 'form-control']) !!}
                @error('ifsc_code')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-lg-3 mb-3">
                {!! Form::label('account_number', 'Account Number') !!}
                {!! Form::text('account_number', null, ['class' => 'form-control']) !!}
                @error('account_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row gap-3  d-flex justify-content-center">
            <div class="form-group  col-lg-5 mb-3">
                {!! Form::label('re_account_number', 'Confirm Account Number') !!}
                {!! Form::text('re_account_number', old('re_account_number'), ['class' => 'form-control', 'id' => 're_account_number']) !!}
                @error('re_account_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-lg-4 mb-3">
                {!! Form::label('pan_card_number', 'Pan Card Number') !!}
                {!! Form::text('pan_card_number', null, ['class' => 'form-control']) !!}
                @error('pan_card_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row gap-3  d-flex justify-content-center">
            <div class="form-group  col-lg-5 mb-3">
                {!! Form::label('pan_card', 'Pan Card') !!}
                {!! Form::file('pan_card', ['class' => 'form-control']) !!}
                <img id="panCardPreview" src="#" alt="Pan Card Preview" style="display: none; width: 100px;height: 100px;;">
                <div id="panpreview" class="preview-container" style="display: none; width: 100px;height: 100px;;"></div>
                @error('pan_card')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group  col-lg-4 mb-3">
                {!! Form::label('business_card', 'Business Card Images') !!}
                {!! Form::file('business_card[]', ['class' => 'form-control', 'multiple' => true, 'accept' => 'image/jpeg,image/png,application/pdf', 'id' => 'business_card']) !!}
                <img id="businessCardPreview" src="#" alt="Business Card Preview" style="display: none; width: 100px;height: 100px;">
                <div id="businesspreview" class="preview-container" style="display: none; width: 100px;height: 100px;"></div>
                @error('business_card')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row gap-3  d-flex justify-content-center">
            <div class="form-group  col-lg-5 mb-3">
                {!! Form::label('bank_details', 'Bank Details') !!}
                {!! Form::file('bank_details', ['class' => 'form-control', 'id' => 'bank_details']) !!}
                <img id="bankDetailsPreview" src="#" alt="Bank Details" style="display: none; width: 100px;height: 100px;">
                <div id="bankpreview" class="preview-container" style="display: none; width: 100px;height: 100px;"></div>
                @error('bank_details')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group  col-lg-4 mb-3">
                {!! Form::label('memo', 'Others') !!}
                {!! Form::file('memo', ['class' => 'form-control']) !!}
                <img id="memoPreview" src="#" alt="Others Preview" style="display: none; width: 100px;height: 100px;">
                <div id="memoFilePreview" class="preview-container" style="display: none; width: 100px;height: 100px;"></div>
                @error('memo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group mt-3  d-flex justify-content-center">
            <label for="remarks">Remarks:</label>
            <textarea class="form-control form-control-sm {{ $errors->has('remarks') ? 'is-invalid' : '' }}" id="remarks" name="remarks" rows="3"></textarea>
            @if ($errors->has('remarks'))
                <div class="invalid-feedback">
                    {{ $errors->first('remarks') }}
                </div>
            @endif
        </div>
        <br>
        <div class="row gap-3  d-flex justify-content-center">
            <div class="form-group  col-lg-3 mb-3">
                {!! Form::label('eway_bill', 'Eway Bill') !!}
                {!! Form::file('eway_bill', ['class' => 'form-control']) !!}
                <img id="ewayBillPreview" src="#" alt="Eway Bill Preview" style="display: none; max-width: 100%; height: auto;">
                <div id="ewaypreview" class="preview-container" style="display: none; max-width: 100%; height: auto;"></div>
                @error('eway_bill')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group  col-lg-3 mb-3">
                {!! Form::label('trips_invoices', 'Trips Invoices') !!}
                {!! Form::file('trips_invoices', ['class' => 'form-control', 'id' => 'trips_invoices']) !!}
                <img id="tripsInvoicesPreview" src="#" alt="Trips Invoices" style="display: none; max-width: 100%; height: auto;">
                <div id="invoicepreview" class="preview-container" style="display: none; max-width: 100%; height: auto;"></div>
                @error('trips_invoices')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group  col-lg-3 mb-3">
                {!! Form::label('other_document', 'Other Document') !!}
                {!! Form::file('other_document', ['class' => 'form-control']) !!}
                <img id="otherDocumentsPreview" src="#" alt="Other Documents Preview" style="display: none; max-width: 100%; height: auto;">
                <div id="otherspreview" class="preview-container" style="display: none; max-width: 100%; height: auto;"></div>
                @error('other_document')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>


        <div class="form-group d-flex justify-content-center mt-3">
            @if (is_null($indentId))
            {!! Form::submit('Create Supplier', ['class' => 'btn dash1']) !!}
            @else
            {!! Form::submit('On the Road', ['class' => 'btn btn-primary']) !!}
            @endif
        </div>
        {!! Form::close() !!}

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script> -->

<script type="text/javascript">
    $('#contact_number').on('change', function() {
        var contactNumber = $(this).val();
        var baseUrl = window.location.origin;
        jQuery.ajax({
            url: "{{ route('suppliers.details') }}",
            method: 'POST',
            data: {
                contactNumber: contactNumber,
                _token: '{{ csrf_token() }}',
            },
            success: function(result) {

                //var result = JSON.parse(response);
                
                if(result != '') {
                    var businessCard = JSON.parse(result.business_card);
                    $('#supplier_id').val(result.id);
                    $('#supplier_name').val(result.supplier_name);
                    $('#supplier_type').val(result.supplier_type);
                    $('#company_name').val(result.company_name);
                    $('#bank_name').val(result.bank_name);
                    $('#ifsc_code').val(result.ifsc_code);
                    $('#account_number').val(result.account_number);
                    $('#re_account_number').val(result.re_account_number);
                    $('#pan_card_number').val(result.pan_card_number);
                    var previewDiv = document.getElementById('businesspreview');
                    var pancardPreviewDiv = document.getElementById('panpreview');
                    var bankPreviewDiv = document.getElementById('bankpreview');
                    var ewayPreviewDiv = document.getElementById('ewaypreview');
                    var invoicePreviewDiv = document.getElementById('invoicepreview');
                    var othersPreviewDiv = document.getElementById('otherspreview');
                    var businessPreviewDiv = document.getElementById('businesspreview');
                    var memoPreviewDiv = document.getElementById('memoFilePreview');
                    
                    var businessCardUrl;
                    var businessCardFileType = 'image';
                    var panCardFileType = 'image';
                    var bankDetailsFileType = 'image';
                    var ewayBillFileType = 'image';
                    var tripsInvoiceFileType = 'image';
                    var otherDocFileType = 'image';
                    var ewayBillFileType = 'image';
                    var memoFileType = 'image';


                    var businessImage = JSON.parse(result.business_card);

                    var businessCardImg = businessImage.map(function(image) {
                        businessCardFileType = getFileExtension(image);
                        
                        businessCardUrl = "{{ asset('/') }}" + image;
                    });
                    var panCardUrl = "{{ asset('/') }}" + result.pan_card;
                    
                    var memoUrl = "{{ asset('/') }}" + result.memo;
                    
                    var ewayBillUrl = "{{ asset('/') }}" + result.eway_bill;
                    var tripsInvoiceUrl = "{{ asset('/') }}" + result.trips_invoices;
                    var otherDocUrl = "{{ asset('/') }}" + result.other_document;
                    var bankDocUrl = "{{ asset('/') }}" + result.bank_details;
                    console.log(result.bank_details);
                    panCardFileType = getFileExtension(result.pan_card);
                    memoFileType = 'pdf'; //getFileExtension(result.memo);
                    bankDetailsFileType = (result.bank_details != null) ? getFileExtension(result.bank_details) : '';

                    if(panCardFileType === 'pdf') {
                        $('#businessCardPreview').hide();
                        $('#panpreview').show();
                        pancardPreviewDiv.innerHTML = '';
                        const imgElement = document.createElement('img');
                        imgElement.height = 100; // Set the height to 100 pixels
                        imgElement.width = 100; // Set the width to 100 pixels
                        imgElement.src = '/images/dashboard/pdf.png';
                        pancardPreviewDiv.appendChild(imgElement);
                    } else {
                        $('#panCardPreview').attr('src', panCardUrl);
                        $('#panCardPreview').show();
                    }
                    
                    if (businessCardFileType === 'pdf') {
                        $('#businessCardPreview').hide();
                        $('#businesspreview').show();
                        previewDiv.innerHTML = '';
                        const imgElement = document.createElement('img');
                        imgElement.height = 100; // Set the height to 100 pixels
                        imgElement.width = 100; // Set the width to 100 pixels
                        imgElement.src = '/images/dashboard/pdf.png';
                        previewDiv.appendChild(imgElement);
                    } else {
                        $('#preview').hide();
                        $('#businessCardPreview').attr('src', businessCardUrl);
                        $('#businessCardPreview').show();
                    }
                    
                    if(bankDetailsFileType === 'pdf') {
                        $('#bankDetailsPreview').hide();
                        $('#bankpreview').show();
                        bankPreviewDiv.innerHTML = '';
                        const imgElement = document.createElement('img');
                        imgElement.height = 100; // Set the height to 100 pixels
                        imgElement.width = 100; // Set the width to 100 pixels
                        imgElement.src = '/images/dashboard/pdf.png';
                        bankPreviewDiv.appendChild(imgElement);
                    } else {
                        $('#bankDetailsPreview').attr('src', bankDocUrl);
                        $('#bankDetailsPreview').show();
                    }

                    if(memoFileType === 'pdf') {
                        $('#memoPreview').hide();
                        $('#memoFilePreview').show();
                        memoPreviewDiv.innerHTML = '';
                        const imgElement = document.createElement('img');
                        imgElement.height = 100; // Set the height to 100 pixels
                        imgElement.width = 100; // Set the width to 100 pixels
                        imgElement.src = '/images/dashboard/pdf.png';
                        memoPreviewDiv.appendChild(imgElement);
                    } else {
                        $('#memoPreview').attr('src', memoUrl);
                        $('#memoPreview').show();
                    }
                    
                    $('#remarks').text(result.remarks);
                } else {
                    $('#supplier_id').val('');
                    $('#contact_number').attr('id', 'contact_number1');
                    $('#supplier_name').val('');
                    $('#supplier_type').val('');
                    $('#company_name').val('');
                    $('#bank_name').val('');
                    $('#ifsc_code').val('');
                    $('#account_number').val('');
                    $('#re_account_number').val('');
                    $('#pan_card_number').val('');
                    $('#pan_card').find('img').remove();
                    $('#memo').find('img').remove();
                    $('#business_card').find('img').remove();
                    $('#panCardPreview').hide();
                    $('#businessCardPreview').hide();
                    $('#memoPreview').hide();
                    $('#ewayBillPreview').hide();
                    $('#tripsInvoicesPreview').hide();
                    $('#otherDocumentsPreview').hide();
                    $('#remarks').text('');
                    toastr.error('Supplier Details not found, Please create a new supplier');
                }
                
            },
            error: function(error) {
                console.error(error.responseText);
            }
        });
    });

    function getFileExtension(url) {
        return url.split('.').pop().toLowerCase();
    }
</script>
    @endsection