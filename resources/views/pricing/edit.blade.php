<!-- <a href="{{ route('pricings.edit', $pricing->id) }}" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#updatePricingModal">
    Update
</a> -->

<a href="#" data-pricing-id="{{ $pricing->id }}" data-bs-toggle="modal" data-bs-target="#updatePricingModal_{{ $pricing->id }}"><i class="fa fa-edit" style="font-size:17px;"></i></a>

<!-- Modal -->
<div class="modal fade" id="updatePricingModal_{{ $pricing->id }}" tabindex="-1" aria-labelledby="updatePricingModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable"  role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#F98917;color:white">
                <h5 class="modal-title" id="updatePricingModalLabel">Update Pricing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <h2>Edit Pricing</h2>

                    {!! Form::model($pricing, ['route' => ['pricings.update', $pricing->id], 'method' => 'POST']) !!}
                    @method('PUT')

                    {!! Form::hidden('update_pickup_id', $pricing->id, ['class' => 'form-control', 'id' => 'update_pickup_id']) !!}

                    <div class="form-group mb-3">
                        {!! Form::label('pickup_city', 'Pickup City') !!}
                        {!! Form::text('pickup_city', $pricing->pickup_city, ['class' => 'form-control', 'id' => 'update_pickup_city']) !!}
                    </div>

                    <div class="form-group mb-3">
                        {!! Form::label('drop_city', 'Drop City') !!}
                        {!! Form::text('drop_city', $pricing->drop_city, ['class' => 'form-control', 'id' => 'update_drop_city']) !!}
                    </div>


                    <div class="form-group mb-3">
                        {!! Form::label('vehicle_type', 'Vehicle Type') !!}
                        {!! Form::select('vehicle_type', $truckTypes->pluck('name', 'id')->prepend('Select', ''), $pricing->vehicle_type, ['class' => "form-control form-control-sm", 'id' => 'truck_type_id']) !!}
                    </div>


                    <div class="mb-3">
                        {!! Form::label('body_type', 'Body Type', ['class' => 'form-label']) !!}
                        {!! Form::select('body_type', [
                        'Open' => 'Open',
                        'Container' => 'Container',
                        ], $pricing->body_type, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group mb-3">
                        {!! Form::label('rate_from', 'Rate From') !!}
                        {!! Form::text('rate_from', $pricing->rate_from, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group mb-3">
                        {!! Form::label('rate_to', 'Rate To') !!}
                        {!! Form::text('rate_to', $pricing->rate_to, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('remarks', 'Remarks:') !!}
                        {!! Form::textarea('remarks', $pricing->remarks, ['class' => 'form-control', 'id' => 'remarks', 'rows' => 3]) !!}
                    </div>


                    <div class="form-group d-grid gap-3 mt-3">
                        {!! Form::submit('Update Pricing', ['class' => 'btn dash1']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

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
        var pricingId = document.getElementById('update_pickup_id');

        var pickupInput = document.getElementById('update_pickup_city');
        var dropInput = document.getElementById('update_drop_city');

        var pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput, {
            types: ['(cities)']  // This restricts the autocomplete to cities.
        });

        pickupAutocomplete.addListener('place_changed', function() {
            var place = pickupAutocomplete.getPlace();
            var city = getCityName(place);
            pickupInput.value = city;
            console.log("Pickup City: ", city);
        });

        var dropAutocomplete = new google.maps.places.Autocomplete(dropInput, {
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

</script>