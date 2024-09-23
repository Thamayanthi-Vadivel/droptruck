<div class="container-fluid">
    <form action="{{ route('indents.store') }}" method="POST" id="indentForm">
        @csrf
        <div class="d-flex">
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group mt-1 col-md-6">
                        <label for="number_1">Customer Number 1:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm  @error('number_1') is-invalid @enderror" id="number_1" name="number_1" pattern="[0-9]{10}" title="Please enter a 10-digit number" required>
                        @error('number_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-1 col-md-6">
                        <label for="customer_name">Customer Name:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" required>
                        @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group mt-1 col-md-6">
                        <label for="company_name">Company Name:</label>
                        <input type="text" class="form-control form-control-sm" id="company_name" name="company_name">
                    </div>

                    <div class="form-group mt-1 col-md-6">
                        <label for="number_2">Customer Number 2:</label>
                        <input type="text" class="form-control form-control-sm" id="number_2" name="number_2" pattern="[0-9]{10}" title="Please enter a 10-digit number">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group mt-1 col-md-6" id="source_type_option">
                        <label for="source_of_lead">Source of Lead:</label>
                        <select class="form-select form-select-sm" id="source_of_lead" name="source_of_lead">
                            <option value="select">select</option>
                            <option value="Old data">Old data</option>
                            <option value="Telecalling">Telecalling</option>
                            <option value="Whatsapp">WhatsApp Blast</option>
                            <option value="Justdial">Justdial</option>
                            <option value="Referal">Referal</option>
                            <option value="SMS">SMS</option>
                            <option value="Webpage">Webpage</option>
                            <option value="Field visit">Field visit</option>
                            <option value="Trade Centre">Trade Centre</option>
                            <option value="Sulekha">Sulekha</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group mt-1 col-md-6" id="new_source_type_text" style="display:none;">
                        <label for="new_material_type">Source of Lead</label>
                        <input type="text" class="form-control form-control-sm" id="new_source_type" name="new_source_type"  placeholder="Please Enter Source Type">
                    </div>


                    <div class="form-group mt-1 col-md-6"  id="body_type_option">
                        <label for="body_type">Body Type: <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm @error('body_type') is-invalid @enderror" id="body_type" name="body_type" required>
                            <option value="">select</option>
                            <option value="Open">Open</option>
                            <option value="Container">Container</option>
                            <option value="JCB - ( HALF BODY)">JCB - ( HALF BODY)</option>
                            <option value="Any">Any</option>
                            <option value="Others">Others</option>
                        </select>
                        @error('body_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-1 col-md-6" id="new_body_type_text" style="display:none;">
                        <label for="new_material_type">Body Type</label>
                        <input type="text" class="form-control form-control-sm" id="new_body_type" name="new_body_type" placeholder="Please Enter Body Type">
                    </div>
                </div>
            </div>

            <div class="col-md-6 ms-2">
                <div class="form-group mt-1" id="truck_type_option">
                    <label for="truck_type_id">Truck Type <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select name="truck_type_id" id="truck_type_id" class="form-control form-control-sm @error('truck_type_id') is-invalid @enderror" required>
                            <option value="">Select</option>
                            @foreach ($truckTypes as $truckType)
                            <option value="{{ $truckType->id }}">{{ $truckType->name }}</option>
                            @endforeach
                        </select>
                        <!-- <div class="input-group-append">
                            <button type="button" class="btn btn-warning btn-sm text-white" id="addTruckType"><i class="fa fa-plus" style="font-size:24px"></i></button>
                        </div> -->
                    </div>
                    @error('truck_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mt-1" id="new_truck_type_text" style="display:none;">
                    <label for="new_material_type">Truck Type</label>
                    <input type="text" class="form-control form-control-sm" id="new_truck_type" name="new_truck_type" placeholder="Please Enter Truck Type">
                </div>

                <div class="form-group mt-1" id="material_type_option">
                    <label for="material_type_id">Material Type</label>
                    <div class="input-group">
                        <select name="material_type_id" id="material_type_id" class="form-control form-control-sm @error('material_type_id') is-invalid @enderror">
                            <option value="">Select</option>
                            @foreach ($materialTypes as $materialType)
                            <option value="{{ $materialType->id }}">{{ $materialType->name }}</option>
                            @endforeach
                        </select>
                        <!-- <div class="input-group-append">
                            <button type="button" class="btn btn-warning btn-sm text-white" id="addMaterialType"><i class="fa fa-plus" style="font-size:24px"></i></button>
                        </div> -->
                    </div>
                    @error('material_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mt-1" id="new_material_type_text" style="display:none;">
                        <label for="new_material_type">Material Type</label>
                        <input type="text" class="form-control form-control-sm" id="new_material_type" name="new_material_type"  placeholder="Please Enter Material Type">
                    </div>

                <div class="row">
                    <div class="form-group mt-1 col-md-6">
                        <label for="weight">Weight:<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm @error('weight') is-invalid @enderror" id="weight" name="weight" required>
                            <select name="weight_unit" id="weight_unit" class="form-select form-select-sm" required>
                                <option value="">Select Weight</option>
                                <option value="kg" {{ old('weight_unit') === 'kg' ? 'selected' : '' }}>kg</option>
                                <option value="tons" {{ old('weight_unit') === 'tons' ? 'selected' : '' }}>tons</option>
                            </select>
                            @error('weight_unit')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('weight_unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group mt-1 col-md-6">
                        <label for="pod_soft_hard_copy">POD Soft / Hard Copy:</label>
                        <select class="form-select form-select-sm" id="pod_soft_hard_copy" name="pod_soft_hard_copy">
                            <option value="select">select</option>
                            <option value="Soft Copy">Soft Copy</option>
                            <option value="Hard Copy">Hard Copy</option>
                            <option value="Not Required">Not Required</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 ms-2">
                <div class="form-group mt-1">
                    <label for="payment_terms">Payment Terms</label>
                    <div class="input-group">
                        <select name="payment_terms" id="payment_terms" class="form-select form-select-sm">
                            <option value="select">select</option>
                            <option value="90/10">90/10</option>
                            <option value="50/50">50/50</option>
                            <option value="COD">COD</option>
                            <option value="Credit">Credit</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" id="pickup_city" name="pickup_city">
        <input type="hidden" id="drop_city" name="drop_city">

         <div class="form-group mt-1">
            <label for="pickup_location">Pickup Location:<span class="text-danger">*</span></label>
             <!-- <input id="pickup_locations" placeholder="Enter a location" type="text"> -->
            <input class="form-control form-control-sm pac-target-input" type="text" name="pickup_location" id="pickup_location" required>
            <!-- <input type="text" class="form-control form-control-sm @error('pickup_location') is-invalid @enderror" id="pickup_locations" name="pickup_locations" required> -->
            @error('pickup_location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-1">
            <label for="drop_location">Drop Location:<span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm @error('drop_location') is-invalid @enderror" id="drop_location" name="drop_location" autocomplete="off" required>
            @error('drop_location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="form-group mt-1">
                <label for="remarks">Remarks:</label>
                <textarea class="form-control form-control-sm" id="remarks" name="remarks"></textarea>
            </div>
            <div class="mt-3 p-2 d-grid gap-3">
                <button type="submit" class="btn" style="background-color: #F98917;border-radius: 20px;border:none;">Submit</button>
            </div>
        </div>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<style>
    .ui-autocomplete {
        z-index: 10000;
        /* Set an even higher z-index value */
    }

.pac-container {
    background-color: #FFF;
    z-index: 20;
    position: fixed;
    display: inline-block;
    float: left;
}
.modal{
    z-index: 20;   
}
.modal-backdrop{
    z-index: 10;        
}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
// function initializeAutocomplete(inputId, hiddenInputId) {
//     $("#" + inputId).autocomplete({
//         source: function(request, response) {
//             $.ajax({
//                 url: "{{ route('locations.autocomplete') }}",
//                 dataType: "json",
//                 data: {
//                     search: request.term
//                 },
//                 success: function(data) {
//                     response(data.map(item => ({
//                         label: item.district,
//                         value: item.district,
//                         id: item.id
//                     })));
//                 },
//                 error: function(xhr, status, error) {
//                     console.error('Error:', error);
//                 }
//             });
//         },
//         minLength: 1,
//         select: function(event, ui) {
//             $('#' + hiddenInputId).val(ui.item.id);
//         }
//     }).data("ui-autocomplete")._renderItem = function(ul, item) {
//         return $("<li>")
//             .append("<div style='font-size: 12px;'>" + item.label + "</div>")
//             .appendTo(ul);
//     };
// }

$(function() {
    // initializeAutocomplete('pickup_location', 'pickup_location_id');
    // initializeAutocomplete('drop_location', 'drop_location_id');
});


</script>
<script>
    $(document).ready(function() {
        $('#addTruckType').on('click', function() {
            addNewOption('truck_type_id', 'Truck Type', "{{ route('trucks.store') }}");
        });

        $('#addMaterialType').on('click', function() {
            addNewOption('material_type_id', 'Material Type', "{{ route('materials.store') }}");
        });

        function addNewOption(selectId, label, storeRoute) {
            var newValue = prompt('Enter new ' + label);
            if (newValue) {
                $.ajax({
                    url: storeRoute,
                    method: 'POST',
                    data: {
                        name: newValue,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        // Assuming the response contains the new type's ID and name
                        $('#' + selectId).append('<option value="' + response.id + '">' + response.name + '</option>');
                        $('#' + selectId).val(response.id);
                    },
                    error: function(error) {
                        console.error('Error storing new ' + label + ': ' + error.responseText);
                    }
                });
            }
        }
    });
</script>


<script>

    document.addEventListener("DOMContentLoaded", function() {
        var pickupInput = document.getElementById('pickup_city');
        var dropInput = document.getElementById('drop_city');
        
        var input = document.getElementById('pickup_location');
        new google.maps.places.Autocomplete(input);
    
        var input1 = document.getElementById('drop_location');
        new google.maps.places.Autocomplete(input1);
        
        var pickupAutocomplete = new google.maps.places.Autocomplete(input, {
            types: ['(cities)']  // This restricts the autocomplete to cities.
        });

        pickupAutocomplete.addListener('place_changed', function() {
            var place = pickupAutocomplete.getPlace();
            var city = getCityName(place);
            pickupInput.value = city;
            console.log("Pickup City: ", city);
        });

        var dropAutocomplete = new google.maps.places.Autocomplete(input1, {
            types: ['(cities)']  // This restricts the autocomplete to cities.
        });

        dropAutocomplete.addListener('place_changed', function() {
            var place = dropAutocomplete.getPlace();
            var city = getCityName(place);
            dropInput.value = city;
            console.log("Drop City: ", city);
        });

        function getCityName(place) {
            for (var i = 0; i < place.address_components.length; i++) {
                var component = place.address_components[i];
                if (component.types.includes("locality")) {
                    return component.long_name;
                }
            }
            return place.name;  // Fallback to place name if locality is not found.
        }
});
  

$('#number_1').on('change', function() {
        var contactNumber = $(this).val();
        var baseUrl = window.location.origin;
        jQuery.ajax({
            url: "{{ route('customer.details') }}",
            method: 'POST',
            data: {
                contactNumber: contactNumber,
                _token: '{{ csrf_token() }}',
            },
            success: function(result) {
                if(result != '') {
                    $('#number_1').val(result.contact_number);
                    $('#customer_name').val(result.customer_name);
                    $('#company_name').val(result.company_name);
                } else {
                    //$('#number_1').val('');
                    $('#customer_name').val('');
                    $('#company_name').val('');
                }
                
            },
            error: function(error) {
                console.error(error.responseText);
            }
        });
    });

$(document).on('change', '#material_type_id', function() {
    var indentId = $('#indent_id').val();
    var reason = $(this).find("option:selected").text();

    if(reason === 'Others') {
        $('#new_material_type_text').css('display', 'block');
        $('#material_type_option').css('display', 'none');

    } else {
        $('#new_material_type_text').css('display', 'none');
        $('#material_type_option').css('display', 'block');
    }
});

$(document).on('change', '#truck_type_id', function() {
    var truckType = $(this).find("option:selected").text();

    if(truckType === 'Others') {
        $('#new_truck_type_text').css('display', 'block');
        $('#truck_type_option').css('display', 'none');

    } else {
        $('#new_truck_type_text').css('display', 'none');
        $('#truck_type_option').css('display', 'block');
    }
});

$(document).on('change', '#source_of_lead', function() {
    var sourceOfLead = $(this).find("option:selected").text();

    if(sourceOfLead === 'Others') {
        $('#new_source_type_text').css('display', 'block');
        $('#source_type_option').css('display', 'none');

    } else {
        $('#new_source_type_text').css('display', 'none');
        $('#source_type_option').css('display', 'block');
    }
});

$(document).on('change', '#body_type', function() {
    var bodyType = $(this).find("option:selected").text();

    if(bodyType === 'Others') {
        $('#new_body_type_text').css('display', 'block');
        $('#body_type_option').css('display', 'none');

    } else {
        $('#new_body_type_text').css('display', 'none');
        $('#body_type_option').css('display', 'block');
    }
});
</script>