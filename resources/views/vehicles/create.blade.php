<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F98917;">
                <h5 class="modal-title" id="customerModalLabel">Add Vehicles</h5>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'vehicles.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                {{-- Vehicle Number --}}
                <div class="form-group mb-3">
                    {!! Form::label('vehicle_number', 'Vehicle Number') !!}
                    {!! Form::text('vehicle_number', null, ['class' => 'form-control', 'required']) !!}
                </div>

                {{-- Vehicle Type --}}
                <div class="form-group mb-3">
                    {!! Form::label('vehicle_type', 'Vehicle Type') !!}
                    {!! Form::select('vehicle_type', $truckTypes->pluck('name', 'id')->prepend('Select', ''), null, ['class' => "form-control form-control-sm", 'id' => 'truck_type_id']) !!}
                </div>


                {{-- Body Type --}}
                <div class="form-group mb-3">
                    {!! Form::label('body_type', 'Body Type') !!}
                    {!! Form::select('body_type', [
                    '' => 'select body type',
                    'Open' => 'Open',
                    'Container' => 'Container',
                    ], null, ['class' => 'form-control form-control-sm']) !!}
                </div>


                {{-- Tonnage Passing --}}
                <div class="form-group mb-3">
                    {!! Form::label('tonnage_passing', 'Tonnage Passing') !!}
                    {!! Form::text('tonnage_passing', null, ['class' => 'form-control', 'required']) !!}
                </div>

                {{-- Driver Number --}}
                <div class="form-group mb-3">
                    {!! Form::label('driver_number', 'Driver Number') !!}
                    {!! Form::text('driver_number', null, ['class' => 'form-control', 'required']) !!}
                </div>

                {{-- Status --}}
                <div class="form-group mb-3">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', ['1' => 'Active', '2' => 'Inactive'], null, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('rc_book', 'RC Book') !!}
                    {!! Form::file('rc_book', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('driving_license', 'Driving License') !!}
                    {!! Form::file('driving_license', ['class' => 'form-control']) !!}
                </div>
                
                <div class="form-group mb-3">
                    {!! Form::label('vehicle_photo', 'Vehicle Photo') !!}
                    {!! Form::file('vehicle_photo', ['class' => 'form-control']) !!}
                </div>
                
                <div class="form-group mb-3">
                    {!! Form::label('insurance', 'Insurance') !!}
                    {!! Form::file('insurance', ['class' => 'form-control']) !!}
                </div>
                
                <div class="form-group mb-3">
                    {!! Form::label('remarks', 'Remarks:') !!}
                    {!! Form::textarea('remarks', null, ['class' => 'form-control', 'id' => 'remarks', 'rows' => 3]) !!}
                </div>


                {{-- Submit Button --}}
                <div class="form-group d-grid gap-3">
                    {!! Form::submit('Create Vehicle', ['class' => 'btn dash1']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>