<div class="modal fade" id="pricingModal" tabindex="-1" role="dialog" aria-labelledby="pricingModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F98917;">
                <h5 class="modal-title" id="pricingModalLabel">Add Pricing</h5>
            </div>
            <div class="modal-body">
                <div class="container">
                    <h2>Add Pricing</h2>
                    {!! Form::open(['route' => 'pricings.store', 'method' => 'POST']) !!}

                    <div class="form-group mt-3">
                        {!! Form::label('pickup_city', 'Pickup City') !!}
                        {!! Form::text('pickup_city', null, ['class' => 'form-control']) !!}

                        {{-- {!! Form::select('pickup_city', array_combine($cities, $cities), null, ['class' => 'form-control']) !!} --}}
                    </div>

                    <div class="form-group mt-3">
                        {!! Form::label('drop_city', 'Drop City') !!}
                        {!! Form::text('drop_city', null, ['class' => 'form-control']) !!}

                        {{-- {!! Form::select('drop_city', array_combine($cities, $cities), null, ['class' => 'form-control']) !!} --}}
                    </div>


                    <div class="form-group mt-3">
                        {!! Form::label('vehicle_type', 'Vehicle Type') !!}
                        {!! Form::select('vehicle_type', $truckTypes->pluck('name', 'id')->prepend('Select', ''), null, ['class' => "form-control form-control-sm", 'id' => 'truck_type_id']) !!}
                    </div>


                    {{-- Body Type --}}
                    <div class="form-group mt-3">
                        {!! Form::label('body_type', 'Body Type') !!}
                        {!! Form::select('body_type', [
                        'Open' => 'Open',
                        'Container' => 'Container',
                        ], null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group mt-3">
                        {!! Form::label('rate_from', 'Rate From') !!}
                        {!! Form::text('rate_from', null, ['class' => 'form-control', 'step' => '0.01']) !!}
                    </div>

                    <div class="form-group mt-3">
                        {!! Form::label('rate_to', 'Rate To') !!}
                        {!! Form::text('rate_to', null, ['class' => 'form-control', 'step' => '0.01']) !!}
                    </div>

                    <div class="form-group mt-3">
                        <label for="remarks">Remarks:</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>

                    <div class="form-group d-grid gap-3 mt-3">
                        {!! Form::submit('Add Pricing', ['class' => 'btn dash1']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
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
    var input = document.getElementById('pickup_city');
    new google.maps.places.Autocomplete(input);

    var input1 = document.getElementById('drop_city');
    new google.maps.places.Autocomplete(input1);
});

</script>