@extends('layouts.sidebar')

@section('content')
@if(Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif
<?php //echo 'sdsds<pre>'; print_r(); exit; ?>
<div class="container mt-3">
    <div class="card">
        <h1 class="btn dash1 text-white fw-bolder">Vehicle Details</h1>
        <div class="ms-2">
            <button type="button" class="btn btn-danger m-1" style="font-size: 8px; padding: 5px 10px;">
                <a href="/confirmed-trips">Confirmed Trips</a>
            </button>
            <!--<button type="button" class="btn btn-danger mt-1" style="font-size: 8px; padding: 5px 10px;" data-toggle="modal" data-target="#cancelModal">-->
            <!--    Cancel-->
            <!--</button>-->
        </div>
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
        <form action="{{ route('storeDriverDetails') }}" method="post" class=" d-flex justify-content-center mt-3" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-end me-2">
                <div class="col-lg-2 m-2">
                    <input type="hidden" id="indent_id" name="indent_id" value="{{ $indent->id }}">
                    <input type="text" class="form-control btn btn-danger" id="unique_enq_number" name="unique_enq_number" value="{{ $uniqueENQNumber }}" readonly>
                </div>
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <div class="row d-flex justify-content-center">
                        <div class="mb-3 col-lg-5">
                            <label for="driver_name" class="form-label">Vendor Name</label>
                            <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="">
                            <input type="hidden" class="form-control" id="supplier_id" name="supplier_id" value="">
                        </div>
                    </div>
                @endif
                <div class="row d-flex justify-content-center">
                    <div class="mb-3 col-lg-5">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" class="form-control" id="driver_name" name="driver_name">
                    </div>

                    <div class="mb-3 col-lg-5">
                        <label for="driver_number" class="form-label">Driver Number</label>
                        <input type="text" class="form-control" id="driver_number" name="driver_number">
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="mb-3 col-lg-5">
                        <label for="vehicle_number" class="form-label">Vehicle Number</label>
                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number">
                    </div>

                    <div class="mb-3 col-lg-5">
                        <label for="vehicle_type" class="form-label">Body Type</label>
                        <select class="form-select form-select-sm" id="vehicle_type" name="vehicle_type">
                            <option value="select">select</option>
                            <option value="Open">Open</option>
                            <option value="Container">Container</option>
                        </select>
                    </div>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="mb-3 col-lg-5" id="truck_type_option">
                    <label for="truck_type_id">Truck Type </label>
                    <div class="input-group">
                        <select name="truck_type" id="truck_type" class="form-select form-select-sm">
                            <option value="">Select</option>
                            @foreach ($truckTypes as $truckType)
                            <option value="{{ $truckType->id }}">{{ $truckType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mt-1" id="new_truck_type_text" style="display:none;">
                    <label for="new_material_type">Truck Type</label>
                    <input type="text" class="form-control form-control-sm" id="new_truck_type" name="new_truck_type" placeholder="Pleae Enter Truck Type">
                </div>

                    <div class="mb-3 col-lg-5">
                        <label for="driver_base_location" class="form-label">Driver Base Location</label>
                        <input type="text" class="form-control" id="driver_base_location" name="driver_base_location">
                    </div>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="mb-3 col-lg-5">
                        <label for="vehicle_photo" class="form-label">Vehicle Photo</label>
                        <input type="file" class="form-control" id="vehicle_photo" name="vehicle_photo" accept="image/*">
                        <img id="vehiclePhotoPreview" src="#" alt="Vehicle Photo Preview" style="display: none; max-width: 100%; height: 50%;">
                        <div id="vehiclepreview" class="preview-container" style="display: none; max-width: 100%; height: auto;"></div>
                    </div>
                    <div class="mb-3 col-lg-5">
                        <label for="driver_license" class="form-label">Driver License</label>
                        <input type="file" class="form-control" id="driver_license" name="driver_license" accept="image/*">
                        <img id="licenseImgPreview" src="#" alt="License Preview" style="display: none; max-width: 100%; height: auto;">
                        <div id="licensepreview" class="preview-container" style="display: none; max-width: 100%; height: auto;"></div>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="mb-3 col-lg-5">
                        <label for="rc_book" class="form-label">RC Book</label>
                        <input type="file" class="form-control" id="rc_book" name="rc_book" accept="image/*">
                        <img id="rcBookImgPreview" src="#" alt="RC Book Preview" style="display: none; max-width: 100%; height: auto;">
                        <div id="rcbookpreview" class="preview-container" style="display: none; max-width: 100%; height: auto;"></div>
                    </div>

                    <div class="mb-3 col-lg-5">
                        <label for="insurance" class="form-label">Insurance</label>
                        <input type="file" class="form-control" id="insurance" name="insurance" accept="image/*">
                        <img id="insuranceImgPreview" src="#" alt="Insurance Preview" style="display: none; max-width: 100%; height: auto;">
                        <div id="insurancepreview" class="preview-container" style="display: none; max-width: 100%; height: auto;"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mb-3">
                    <div>
                        <button type="submit" class="btn btn-primary d-flex justify-content-center">Move to Loading</button>
                    </div>
                    <!-- <div>
                <button type="button" class="btn dash1 ms-5 justify-content-center">Move to Loading</button>
                </div> -->
                </div>
        </form>
    </div>
</div>
</div>
<!-- Add these links in the head section of your HTML file -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#indent_id').select2({
            allowClear: true,
        });
    });
    $('#vehicle_number').on('change', function() {
    var vehicleNumber = $(this).val();
    // console.log(contactNumber);
    var baseUrl = window.location.origin;
    jQuery.ajax({
        url: "{{ route('driver.details') }}",
        method: 'POST',
        data: {
            vehicleNumber: vehicleNumber,
            _token: '{{ csrf_token() }}',
        },
        success: function(result) {
            if (result) {
                console.log(result);
                $('#driver_number').val(result.driver_number);
                $('#driver_name').val(result.driver_name);
                $('#vehicle_number').val(result.vehicle_number);
                $('#driver_base_location').val(result.driver_base_location);
                $('#vehicle_type').val(result.vehicle_type);
                $('#truck_type').val(result.truck_type);
                //Vehicle Photo
                var previewDiv = document.getElementById('vehiclepreview');
                var vehiclePhotoUrl = "{{ asset('/') }}" + result.vehicle_photo;
                var imageUrl = result.vehicle_photo;
                fetch(imageUrl)
                    .then(response => response.blob())
                    .then(blob => {
                        console.log('File Size:', blob.size); // Log the file size
                        if (blob.size > 2048 * 1024) {
                            console.error('File exceeds maximum size of 2048 KB');
                            return; // Stop further processing
                        }

                        const mimeType = blob.type || 'image/jpeg';
                        const extensionMap = {
                            'image/jpeg': 'jpg',
                            'image/png': 'png',
                            'image/gif': 'gif',
                            'image/svg+xml': 'svg',
                            'application/pdf': 'pdf',
                            'application/msword': 'doc',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'docx'
                        };

                        const fileExtension = 'jpg'; ///extensionMap[mimeType] ||
                        const lastSection = imageUrl.split('/').pop().split('.').shift();
                        var fileName = `${lastSection}.${fileExtension}`;

                        var file = new File([blob], 'images.png', { type: 'jpg' });

                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        console.log(dataTransfer.files);
                        document.getElementById('vehicle_photo').files = dataTransfer.files;
                    });


                $('#vehiclePhotoPreview').attr('src', vehiclePhotoUrl);
                $('#vehiclePhotoPreview').show();
                
                //Insurance
                var previewDiv1 = document.getElementById('insurancepreview');
                var insurancePhotoUrl = "{{ asset('/') }}" + result.insurance;
                var insuranceImage = result.insurance;
                fetch(insuranceImage)
                    .then(response => response.blob())
                    .then(blob => {
                        const mimeType = blob.type || 'image/jpeg';
                        const insuranceImages = insuranceImage.split('/').pop();
                        var file = new File([blob], insuranceImages, { type: mimeType });
                        var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            document.getElementById('driver_license').files = dataTransfer.files;
                });
                $('#insuranceImgPreview').attr('src', insurancePhotoUrl);
                $('#insuranceImgPreview').show();
                
                //RC Book
                var previewDiv2 = document.getElementById('rcbookpreview');
                var rcBookPhotoUrl = "{{ asset('/') }}" + result.rc_book;
                var rcbookImage = result.rc_book;
                fetch(rcbookImage)
                    .then(response => response.blob())
                    .then(blob => {
                        const mimeType = blob.type || 'image/jpeg'; 
                        const rcbookImages = rcbookImage.split('/').pop();
                        var file = new File([blob], rcbookImages, { type: mimeType });
                        var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            document.getElementById('rc_book').files = dataTransfer.files;
                });
                $('#rcBookImgPreview').attr('src', rcBookPhotoUrl);
                $('#rcBookImgPreview').show();
                
                //License
                var previewDiv3 = document.getElementById('licensepreview');
                var licensePhotoUrl = "{{ asset('/') }}" + result.driver_license;
                var licensePhotoImage = result.driver_license;
                fetch(licensePhotoImage)
                    .then(response => response.blob())
                    .then(blob => {
                        const mimeType = blob.type || 'image/jpeg';
                        const licensePhotoImages = licensePhotoImage.split('/').pop();
                        var file = new File([blob], licensePhotoImages, { type: mimeType });
                        var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            document.getElementById('insurance').files = dataTransfer.files;
                });
                $('#licenseImgPreview').attr('src', licensePhotoUrl);
                $('#licenseImgPreview').show();
            } else {
                $('#driver_name').val('');
                $('#driver_number').val('');
                $('#driver_base_location').val('');
                $('#driver_license').val('');
                $('#rc_book').val('');
                $('#insurance').val('');
                $('#vehicle_photo').val('');
                $('#vehiclePhotoPreview').attr('src', '');
                $('#vehiclePhotoPreview').hide();
                
                $('#insuranceImgPreview').attr('src', '');
                $('#insuranceImgPreview').hide();
                
                $('#rcBookImgPreview').attr('src', '');
                $('#rcBookImgPreview').hide();
                
                $('#licenseImgPreview').attr('src', '');
                $('#licenseImgPreview').hide();
               
            }
        },
        error: function(error) {
            console.error(error.responseText);
        }
    });
});
</script>
@endsection