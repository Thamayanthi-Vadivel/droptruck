@extends('layouts.sidebar')

@section('content')
<style>
    th {
        color: blueviolet;
    }

    .btn-warning.custom-active {
        background: linear-gradient(135deg, #007bff, #8a2be2);
        color: #fff;
        border: #8a2be2;
    }

    .bg-gradient-info {
        background-image: radial-gradient(515px at 48.7% 52.8%, rgb(239, 110, 110) 0%, rgb(230, 25, 25) 46.5%, rgb(154, 11, 11) 100.2%);
    }
    .circle-badge {
        border-radius: 50%;
    }
</style>
<div>
        <h2 class="btn btn-primary text-white fw-bolder float-end mt-1">User : {{ auth()->user()->name }}</h2>
    </div>
<div class="m-3">
    <a href="{{ route('indents.index') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Unquoted<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $unquotedIndents->count() }}
    </span></a>
    <a href="{{ route('fetch-last-two-details') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Quoted<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $quotedIndents->count() }}
    </span></a>
    <a href="{{ route('confirmed_locations')}}" class="btn btn-warning custom-active" style="font-size: 12px; padding: 5px 10px;position: relative;">Confirmed
    <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2) 
    {{ $indentsCount }}
    @else
    {{ $indentsCount }}
    @endif
    </span>
</a>
    <a href="{{ route('canceled-indents') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Cancel<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $canceledIndents }}
    </span></a>
    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 3) 
            <a href="{{ route('followup-indents') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Followup
                <span class="badge badge-primary circle-badge text-light" id="followupIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
                {{ $followupIndents->count() }}
                </span>
            </a>
        @endif
</div>

<table class="table table-bordered table-striped table-hover" style="font-size:8px;">
    <thead>
        <tr>
            <th class="bg-gradient-info text-light">Enq No</th>
            @if(auth()->user()->role_id != 4)
            <th class="bg-gradient-info text-light">Customer Name</th>            
            <th class="bg-gradient-info text-light">Company Name</th>
            <th class="bg-gradient-info text-light">Number 1</th>
            @endif
            <th class="bg-gradient-info text-light">Pickup Location</th>
            <th class="bg-gradient-info text-light">Drop Location</th>
            <th class="bg-gradient-info text-light">Material Type</th>
            <th class="bg-gradient-info text-light">Truck Type</th>
            <th class="bg-gradient-info text-light">Body type</th>
            <th class="bg-gradient-info text-light">Weight</th>
            <th class="bg-gradient-info text-light">Rate</th>
            @if(auth()->user()->role_id != 4)
                <th class="bg-gradient-info text-light">Customer Rate</th>
            @endif
            @if(auth()->user()->role_id == 1)
                <th class="bg-gradient-info text-light">Sales Name</th>
                <th class="bg-gradient-info text-light">Supplier Name</th>
            @endif
            <th class="bg-gradient-info text-light">Created By</th>
            <th class="bg-gradient-info text-light">Remarks</th>
            <th class="bg-gradient-info text-light">Created Date</th>
            @if(auth()->user()->role_id != 4)
                <th class="bg-gradient-info text-light">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($indents as $confirmedIndent)
            @php
                $confirmedRate = DB::table('rates')->where('indent_id',$confirmedIndent->id)->where('is_confirmed_rate', 1)->first();
                if($confirmedRate) {
                    $supplierName = DB::table('users')->where('id', $confirmedRate->user_id)->first();
                } else {
                    $supplierName = '';
                }
                
                if($confirmedIndent->new_material_type == null) {
                    $materialType = ($confirmedIndent->materialType) ? $confirmedIndent->materialType->name : 'N/A';
                } else {
                    $materialType = $confirmedIndent->new_material_type;
                }

                if($confirmedIndent->new_body_type == null) {
                    $bodyType = ($confirmedIndent->body_type) ? $confirmedIndent->body_type : 'N/A';
                } else {
                    $bodyType = $confirmedIndent->new_body_type;
                }

                if($confirmedIndent->new_truck_type == null) {
                    $truckType = ($confirmedIndent->truckType) ? $confirmedIndent->truckType->name : 'N/A';
                } else {
                    $truckType = $confirmedIndent->new_truck_type;
                }

                if($confirmedIndent->new_source_type == null) {
                    $sourceType = $confirmedIndent->source_of_lead;
                } else {
                    $sourceType = $confirmedIndent->new_source_type;
                }
            @endphp
        <tr>
            <td>{{ $confirmedIndent->getUniqueENQNumber() }}</td>
            @if(auth()->user()->role_id != 4)
                <td>{{ $confirmedIndent->customer_name }}</td>
                <td>{{ $confirmedIndent->company_name }}</td>
                <td>{{ $confirmedIndent->number_1 }}</td>
            @endif
            <!-- <td>{{ $confirmedIndent->pickupLocation ? $confirmedIndent->pickupLocation->district : 'N/A' }}</td>
            <td>{{ $confirmedIndent->dropLocation ? $confirmedIndent->dropLocation->district : 'N/A' }}</td> -->
            <td>{{ $confirmedIndent->pickup_location_id ? $confirmedIndent->pickup_location_id : 'N/A' }}</td>
            <td>{{ $confirmedIndent->drop_location_id ? $confirmedIndent->drop_location_id : 'N/A' }}</td>
            <td>{{ $materialType }}</td>
            <td>{{ $truckType }}</td>
            <td>{{ $bodyType }}</td>
            <td>{{ $confirmedIndent->weight }} {{ $confirmedIndent->weight_unit }}</td>
            <td>{{ $confirmedIndent->driver_rate}}</td>
            
           @if(auth()->user()->role_id != 4)
           <td>
                @if(optional($confirmedIndent->customerRate)->rate)
                {{ $confirmedIndent->customerRate->rate }}
                @else
                No customer rate found.
                @endif
            </td>
            @endif
            @if(auth()->user()->role_id == 1)
                <td>{{ $confirmedIndent->user ? $confirmedIndent->user->name : 'N/A' }}</td>
                <td>
                    {{ ($supplierName) ? $supplierName->name : 'N/A' }}
                </td>
            @endif
            <td>{{ ($confirmedIndent->createdUser) ? $confirmedIndent->createdUser->name .' - '.$confirmedIndent->createdUser->designation : '' }}</td>
            <td>{{ $confirmedIndent->remarks }}</td>
            <td>{{ $confirmedIndent->created_at }}</td>
            @if(auth()->user()->role_id != 4)
            <td>
                <!-- <button type="button" class="btn btn-sm" style="font-size:8px;background-color:#FFF78A" data-toggle="modal" data-target="#confirmDeleteModal">Lose</button> -->
                <button type="button" class="btn btn-sm" style="font-size:8px;background-color:#FFF78A" onclick="confirmDeleteModal('{{ $confirmedIndent->id }}')">Lose</button>
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $indents->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
<!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                </div>
                <div class="modal-body">
                    {{-- <form class="delete-form" action="{{ route('indents.destroy', $confirmedIndent->id) }}" method="POST"> --}}
                        <form class="delete-form" method="POST">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="cancelReason" class="fw-bolder">Cancel Reason:</label>
                            <select class="form-control" id="reason" name="reason" fdprocessedid="gqtna">
                                <option value="">Select Cancel Reason</option>
                                <option value="Duplicate Enquiry">Duplicate Enquiry</option>
                                <option value="Found Alternate Vehicle">Found Alternate Vehicle</option>
                                <option value="Freight High">Freight High</option>
                                <option value="Just Enquiry">Just Enquiry</option>
                                <option value="Material not ready">Material not ready</option>
                                <option value="Not Responding">Not Responding</option>
                                <option value="Quote Delay">Quote Delay</option>
                                <option value="Quote Not Given">Quote Not Given</option>
                                <option value="Request Credit">Request Credit</option>
                                <option value="Trip Postponed">Trip Postponed</option>
                                <option value="Unavailability of vehicle">Unavailability of vehicle</option>
                                <option value="Will Confirm Later">Will Confirm Later</option>
                                <option value="Others">Others</option>
                                <option value="Followup">Followup</option>
                            </select>

                            <div class="row cancel_reasons" id="cancel_reasons" style="display: none;">
                                <div class="form-group mt-1 col-md-12">
                                    <label for="cancel_reason">Cancel Reason</label>
                                    <input type="text" class="form-control form-control-sm cancel_reason" id="cancel_reason" name="cancel_reason">
                                </div>
                            </div>
                            <div class="row followups" id="followup" style="display: none;">
                                <div class="form-group mt-1 col-md-12">
                                    <label for="followup">Followup Date</label>
                                    <input type="date" class="form-control form-control-sm" id="followup_date" name="followup_date">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger float-end mt-2">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    setTimeout(function(){
       location.reload();
    }, 600000); // 10000 milliseconds = 10 seconds
</script>

<script>
    function submitForm() {
        // Submit the form when the "Delete" button in the modal is clicked
        document.getElementById('loseForm').submit();
    }

    // Show/hide the remarks field in the modal based on the button click
    $('#confirmModal').on('shown.bs.modal', function() {
        $('#remarks').focus();
    });

    $('#confirmModal').on('hidden.bs.modal', function() {
        $('#remarks').val(''); // Clear the textarea when the modal is closed
    });

    function confirmDeleteModal(indentId) {
        var baseUrl = window.location.origin;
        $('.delete-form').attr('action', baseUrl+'/indents/' + indentId);
        $('.cancel_reasons').attr('id', 'cancel_reasons_'+indentId);
        $('.followups').attr('id', 'followup_'+indentId);
        $('#confirmDeleteModal').modal('show');
    }
    
    $(document).on('change', '#reason', function() {
        var indentId = $('#indent_id').val();
        var reason = $(this).val();

        if(reason === 'Others') {
           // alert('Others selected'); // Ensure this part runs
            $('.cancel_reasons').css('display', 'block');
            $('.cancel_reason').attr('required', true);
            $('.followup').attr('required', false);

        } else {
            $('.cancel_reasons').css('display', 'none'); // Hide it if it's not 'Others'
            $('.cancel_reason').attr('required', false);
            $('.followup').attr('required', false);
        }

        if(reason === 'Followup') {
           // alert('Others selected'); // Ensure this part runs
            $('.followups').css('display', 'block');
            $('.cancel_reason').attr('required', true);
            $('.cancel_reason').attr('required', false);
        } else {
            $('.followups').css('display', 'none'); // Hide it if it's not 'Others'
            $('.followup').attr('required', false);
            $('.cancel_reason').attr('required', false);
        }
    });
    
</script>
@endsection