<a href="#" class="btn edit-button" data-indent-id="{{ $indent->id }}" data-bs-toggle="modal" data-bs-target="#editIndentModal_{{ $indent->id }}">
    <i class="fa fa-edit" style="font-size:8px;color:brown"></i>
</a>

@php
    $segmentValue = request()->segment(1);
@endphp

<div class="modal fade" id="editIndentModal_{{ $indent->id }}" tabindex="-1" role="dialog" aria-labelledby="editIndentModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#F98917">
                <h5 class="modal-title" id="editIndentModalLabel">Update Indent</h5>
                <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <h2>Update Indent</h2>
                    <form action="{{ route('indents.update', $indent->id) }}" method="POST" id="indentForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            
                            <div class="col-md-6">
                                @if(auth()->user()->role_id == 1)
                                    <div class="form-group mt-1">
                                        <label for="sales_person">Sales Person:</label>
                                        <select class="form-select" id="sales_person" name="sales_person">
                                            <option value="">Select Sales Person</option>
                                            @foreach($salesPerson as $sales)
                                                <option value="{{ $sales->id }}" {{ $indent->user_id == $sales->id ? 'selected' : '' }} >{{ $sales->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if(auth()->user()->role_id != 1)
                                    <input type="hidden" class="form-control" id="sales_person" name="sales_person" value="{{ $indent->user_id }}">
                                @endif
                                <div class="form-group mt-1">
                                    <label for="customer_name">Customer Name:</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $indent->customer_name }}">
                                </div>

                                <div class="form-group mt-1">
                                    <label for="company_name">Company Name:</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $indent->company_name }}">
                                </div>

                                <div class="form-group mt-1">
                                    <label for="number_1">Number 1:</label>
                                    <input type="text" class="form-control" id="number_1" name="number_1" value="{{ $indent->number_1 }}">
                                </div>

                                <div class="form-group mt-1">
                                    <label for="number_2">Number 2:</label>
                                    <input type="text" class="form-control" id="number_2" name="number_2" value="{{ $indent->number_2 }}">
                                </div>

                                <div class="form-group mt-1">
                                    <label for="source_of_lead">Source of Lead:</label>
                                    <select class="form-select" id="source_of_lead" name="source_of_lead">
                                        <option value="">Select an Option</option>
                                        <option value="Old data" {{ $indent->source_of_lead === 'Old data' ? 'selected' : '' }}>Old data</option>
                                        <option value="Telecalling" {{ $indent->source_of_lead === 'Social Media' ? 'selected' : '' }}>Telecalling</option>
                                        <option value="Whatsapp" {{ $indent->source_of_lead === 'Whatsapp' ? 'selected' : '' }}>WhatsApp Blast</option>
                                        <option value="Justdial" {{ $indent->source_of_lead === 'Justdial' ? 'selected' : '' }}>Justdial</option>
                                        <option value="Referal" {{ $indent->source_of_lead === 'Referal' ? 'selected' : '' }}>Referal</option>
                                        <option value="SMS" {{ $indent->source_of_lead === 'SMS' ? 'selected' : '' }}>SMS</option>
                                        <option value="Webpage" {{ $indent->source_of_lead === 'Webpage' ? 'selected' : '' }}>Webpage</option>
                                        <option value="Field visit" {{ $indent->source_of_lead === 'Field visit' ? 'selected' : '' }}>Field visit</option>
                                        <option value="Trade Centre" {{ $indent->source_of_lead === 'Trade Centre' ? 'selected' : '' }}>Trade Centre</option>
                                        <option value="Sulekha" {{ $indent->source_of_lead === 'Sulekha' ? 'selected' : '' }}>Sulekha</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="form-group mt-1 col-md-6" id="new_source_type_text" style="display:none;">
                                    <label for="new_material_type">Source of Lead</label>
                                    <input type="text" class="form-control form-control-sm" id="new_source_type" name="new_source_type"  placeholder="Please Enter Source Type">
                                </div>
                                
                                
                                    <label for="pickup_location">Pickup Location:</label>
                                    <input class="form-control form-control-sm pac-target-input edit_pickup_locations" type="text" name="edit_pickup_locations" id="edit_pickup_locations"  value="{{ $indent->pickup_location_id }}" {{ $segmentValue === 'quoted' ? 'readonly' : '' }} >
                                    <!-- <input type="text" class="form-control form-control-sm @error('pickup_location') is-invalid @enderror" id="pickup_locations" name="pickup_locations" required> -->
                                    @error('pickup_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                

                                <label for="drop_location_id">Drop Location:</label>
                                <input class="form-control form-control-sm pac-target-input edit_drop_locations" type="text" name="edit_drop_locations" id="edit_drop_locations" value="{{ $indent->drop_location_id }}"  {{ $segmentValue === 'quoted' ? 'readonly' : ''}}>
                                <!-- <select class="form-select" name="drop_location_id" required>
                                    @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ $indent->drop_location_id == $location->id ? 'selected' : '' }}>{{ $location->district }}</option>
                                    @endforeach
                                </select><br> -->

                            </div>
                            <div class="col-md-6">

                                <!-- Truck Type Dropdown -->
                                <div class="form-group">
                                    <label for="truck_type_id">Truck Type</label>
                                    <select name="truck_type_id" id="truck_type_id" class="form-control">
                                        <option value="">Select or Add Truck Type</option>
                                        @foreach ($truckTypes as $truckType)
                                        <option value="{{ $truckType->id }}" {{ $indent->truck_type_id == $truckType->id ? 'selected' : '' }}>
                                            {{ $truckType->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>



                                <div class="form-group mt-1">
                                    <label for="body_type">Body Type:</label>
                                    <select class="form-select" id="body_type" name="body_type">
                                        <option value="Open" {{ $indent->body_type === 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Container" {{ $indent->body_type === 'Container' ? 'selected' : '' }}>Container</option>
                                        <option value="JCB - ( HALF BODY)" {{ $indent->body_type === 'JCB - ( HALF BODY)' ? 'selected' : '' }}>JCB - ( HALF BODY)</option>
                                        <option value="Any" {{ $indent->body_type === 'Any' ? 'selected' : '' }}>Any</option>
                                        <option value="Others" {{ $indent->body_type === 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>

                                <div class="form-group mt-1 col-md-6">
    <label for="weight">Weight:</label>
    <div class="input-group">
        <input type="text" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ $indent->weight }}">
        <select name="weight_unit" id="weight_unit" class="form-select" required>
            <option value="kg" {{ $indent->weight_unit === 'kg' ? 'selected' : '' }}>kg</option>
            <option value="tons" {{ $indent->weight_unit === 'tons' ? 'selected' : '' }}>tons</option>
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

                                {{-- edit.blade.php --}}
                                <!-- Material Type Dropdown -->
                                <div class="form-group">
                                    <label for="material_type_id">Material Type</label>
                                    <select name="material_type_id" id="material_type_id" class="form-control">
                                        <option value="">Select or Add Material Type</option>
                                        @foreach ($materialTypes as $materialType)
                                        <option value="{{ $materialType->id }}" {{ $indent->material_type_id == $materialType->id ? 'selected' : '' }}>
                                            {{ $materialType->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-1">
                                    <label for="pod_soft_hard_copy">POD Soft / Hard Copy:</label>
                                    <select class="form-select form-select-sm" id="pod_soft_hard_copy" name="pod_soft_hard_copy">
                                        <option value="select">select</option>
                                        <option value="Soft Copy" {{ $indent->  pod_soft_hard_copy == 'Soft Copy' ? 'selected' : '' }}>Soft Copy</option>
                                        <option value="Hard Copy" {{ $indent->  pod_soft_hard_copy == 'Hard Copy' ? 'selected' : '' }}>Hard Copy</option>
                                        <option value="Not Required" {{ $indent->   pod_soft_hard_copy == 'Not Required' ? 'selected' : '' }}>Not Required</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-1">
                                <label for="remarks">Remarks:</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ $indent->remarks }}</textarea>
                            </div>

                            <div class="mt-3 d-grid gap-3">
                                <button type="submit" class="btn dash1">Update</button>
                            </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
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

    document.addEventListener("DOMContentLoaded", function() {
    
    var inputs = document.getElementsByClassName('edit_pickup_locations');
    var dropLocation = document.getElementsByClassName('edit_drop_locations');
    
    Array.prototype.forEach.call(inputs, function(input) {
        new google.maps.places.Autocomplete(input);
    });

     Array.prototype.forEach.call(dropLocation, function(droplocations) {
        new google.maps.places.Autocomplete(droplocations);
    });
});
</script>