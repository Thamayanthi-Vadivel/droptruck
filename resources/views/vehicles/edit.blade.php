<a href="{{ route('vehicles.edit', $vehicle->id) }}" data-bs-toggle="modal" data-bs-target="#updateEmployeeModal_{{ $vehicle->id }}"><i class="fa fa-edit" style="font-size:17px;"></i></a>

<div class="modal fade" id="updateEmployeeModal_{{ $vehicle->id }}" tabindex="-1" aria-labelledby="updateEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#F98917">
                <h5 class="modal-title text-white fw-bolder text-center" id="updateEmployeeModalLabel">Update Truck</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::model($vehicle, ['route' => ['vehicles.update', $vehicle->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
                <div class="mb-3">
                    {!! Form::label('vehicle_number', 'Vehicle Number', ['class' => 'form-label']) !!}
                    {!! Form::text('vehicle_number', null, ['class' => 'form-control', 'id' => 'vehicle_number']) !!}
                </div>

                <!-- Additional fields -->
                <div class="form-group mb-3">
                    {!! Form::label('vehicle_type', 'Vehicle Type') !!}
                    {!! Form::select('vehicle_type', $truckTypes->pluck('name', 'id')->prepend('Select', ''), $vehicle->vehicle_type, ['class' => "form-control form-control-sm", 'id' => 'truck_type_id']) !!}
                </div>


                <div class="mb-3">
                    {!! Form::label('body_type', 'Body Type', ['class' => 'form-label']) !!}
                    {!! Form::select('body_type', [
                    'Open' => 'Open',
                    'Container' => 'Container',
                    ], null, ['class' => 'form-control']) !!}
                </div>


                <div class="mb-3">
                    {!! Form::label('tonnage_passing', 'Tonnage Passing', ['class' => 'form-label']) !!}
                    {!! Form::text('tonnage_passing', null, ['class' => 'form-control', 'id' => 'tonnage_passing']) !!}
                </div>

                <div class="mb-3">
                    {!! Form::label('driver_number', 'Driver Number', ['class' => 'form-label']) !!}
                    {!! Form::text('driver_number', null, ['class' => 'form-control', 'id' => 'driver_number']) !!}
                </div>

                <div class="mb-3">
                    {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                    {!! Form::select('status', [
                    '1' => 'Active',
                    '2' => 'Inactive',
                    ], $vehicle->status, ['class' => 'form-control', 'id' => 'status']) !!}
                </div>

                <div class="mb-3">
                    {!! Form::label('rc_book', 'RC Book') !!}
                    {!! Form::file('rc_book', ['class' => 'form-control', 'id' => 'rc_book']) !!}
                </div>
                @if($vehicle->rc_book)
                    <p >
                        <img id="rcBookPreview" src="{{ asset($vehicle->rc_book) }}" alt="RC Book Preview" style="height:100px; width:100px;">
                    </p>
                @endif

                <div class="mb-3">
                    {!! Form::label('driving_license', 'Driving License') !!}
                    {!! Form::file('driving_license', ['class' => 'form-control', 'id' => 'driving_license']) !!}
                </div>
                @if(optional($vehicle)->driving_license)
                <p>
                    <img id="drivingLicensePreview" src="{{ asset(optional($vehicle)->driving_license) }}" alt="Driving License Preview" style="height:100px; width:100px;">
                </p>
            @endif


                <div class="form-group mb-3">
                            <label for="remarks">Remarks:</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3">{{  optional($vehicle)->remarks }}</textarea>
                        </div>


                <div class="form-group d-grid gap-3">
                    {!! Form::submit('Update', ['class' => 'btn dash1']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>