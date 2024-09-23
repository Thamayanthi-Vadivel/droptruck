    <!-- resources/views/indents/index.blade.php -->

    @extends('layouts.sidebar')<!-- Assuming you have a layout file -->

    @section('content')
    <style>
        .btn-warning.custom-active {
            background: linear-gradient(135deg, #007bff, #8a2be2);
            color: #fff;
            border: #8a2be2;
        }

        .small-input {
            width: 150px;
            /* Adjust the width as needed */
            font-size: 0.8rem;
            /* Adjust the font size as needed */
        }
        .circle-badge {
        border-radius: 50%;
    }

        .bg-gradient-info {
            background-image: radial-gradient(515px at 48.7% 52.8%, rgb(239, 110, 110) 0%, rgb(230, 25, 25) 46.5%, rgb(154, 11, 11) 100.2%);
        }
    </style>

    <?php //echo 'followup<pre>'; ?>
    <div>
        <h2 class="btn btn-primary text-white fw-bolder float-end mt-1">User: {{ auth()->user()->name }}</h2>
    </div>
    <div class="d-flex justify-content-between">
        <div class="m-3">
            <a href="{{ route('indents.index') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Unquoted
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $indents->count() }}
    </span>
        </a>
            <a href="{{ route('fetch-last-two-details') }}" class="btn btn-warning " style="font-size: 12px; padding: 5px 10px;position: relative;">Quoted<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $quotedIndents->count() }}
    </span></a>
            <a href="{{ route('confirmed_locations')}}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Confirmed<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $confirmedIndents }}
    </span></a>
            <a href="{{ route('canceled-indents') }}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Cancel<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $canceledIndents->count() }}
    </span></a>
    
        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 3) 
            <a href="{{ route('followup-indents') }}" class="btn btn-warning custom-active" style="font-size: 12px; padding: 5px 10px;position: relative;">Followup
                <span class="badge badge-primary circle-badge text-light" id="followupIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
                @if(auth()->user()->role_id == 1) 
                    {{ $followupIndentsCount->count() }}
                @else
                    {{ $followupIndents->count() }}
                @endif
                </span>
            </a>
        @endif
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <table class="table table-bordered table-striped table-hover" style="font-size:8px;">
        <thead>
            <tr>
                <th class="bg-gradient-info text-light">Enq No</th>
                @if(auth()->user()->role_id != 4)
                <th class="bg-gradient-info text-light">Company Name</th>
                <th class="bg-gradient-info text-light">Customer Name</th>
                <th class="bg-gradient-info text-light">Customer Number</th>
                @endif
                <th class="bg-gradient-info text-light">Pickup Location</th>
                <th class="bg-gradient-info text-light">Drop Location</th>
                <th class="bg-gradient-info text-light">Truck type</th>
                <th class="bg-gradient-info text-light">Body type</th>
                <th class="bg-gradient-info text-light">Weight</th>
                <th class="bg-gradient-info text-light">Material Type</th>
                <th class="bg-gradient-info text-light">HardCopy</th>
                <th class="bg-gradient-info text-light">Rate L1</th>
                <th class="bg-gradient-info text-light">Rate L2</th>
                <th class="bg-gradient-info text-light">Created By</th>
                <th class="bg-gradient-info text-light">Remarks</th>
                @if(auth()->user()->role_id != 4)
                    <th class="bg-gradient-info text-light">Source of Lead</th>
                @endif
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <th class="bg-gradient-info text-light">Salesperson</th>
                @endif
                <th class="bg-gradient-info text-light">Created Date</th>
                <th class="bg-gradient-info text-light">Followup Date</th>
                <th class="bg-gradient-info text-light">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($followupIndents as $indent)
            <tr>
                <td>{{ $indent->getUniqueENQNumber() }}</td>
                @if(auth()->user()->role_id != 4)
                <td>{{ $indent->company_name }}</td>
                <td>{{ $indent->customer_name }}</td>
                <td>{{ $indent->number_1 }}</td>
                @endif
                <td>{{ $indent->pickupLocation ? $indent->pickupLocation->district : $indent->pickup_location_id }}</td>
                <td>{{ $indent->dropLocation ? $indent->dropLocation->district : $indent->drop_location_id }}</td>

                <td>{{ $indent->truckType ? $indent->truckType->name : 'N/A' }}</td>
                <td>{{ $indent->body_type }}</td>
                <td>{{ $indent->weight }} {{ $indent->weight_unit }}</td>

                <td>{{ $indent->materialType ? $indent->materialType->name : 'N/A' }}</td>

                <td>{{ $indent->pod_soft_hard_copy }}</td>
                <td>
                    @if($indent->indentRate->isNotEmpty())
                        {{ $indent->indentRate->sortBy('rate')->first()->rate }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @php
                        $secondMinRate = $indent->indentRate() // Assuming 'indentRate' is the relationship method
                            ->where('is_confirmed_rate', 0) // Add any additional conditions as needed
                            ->orderBy('rate', 'asc')
                            ->skip(1) // Skip the first rate (minimum rate)
                            ->take(1) // Take the first rate from the remaining collection
                            ->pluck('rate') // Pluck the 'rate' attribute
                            ->first();
                    @endphp
                    
                    @if ($secondMinRate !== null)
                        {{ $secondMinRate }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ ($indent->createdUser) ? $indent->createdUser->name .' - '.$indent->createdUser->designation : '' }}</td>
                <td>{{ $indent->remarks }}</td>
                @if(auth()->user()->role_id != 4)
                    <td>{{ $indent->source_of_lead }}</td>
                @endif
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <td>{{ $indent->user ? $indent->user->name : 'N/A' }}</td>
                @endif
                <td>{{ $indent->created_at }}</td>
                <td>{{ $indent->followup_date }}</td>
                <td class="d-flex">
                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
                    <div>@include('indent.delete')</div>
                    <button type="button" class="btn btn-sm" id="restore-indent" onclick="restoreIndent('{{ $indent->id }}')"><i class="fa fa-recycle" style="font-size:8px;"></i></button>
                    <a href="{{ route('indents.show', ['indent' => $indent->id, 'page' => '1']) }}" class="btn"><i class="fa fa-eye" style="font-size:8px;color:darkblue"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
     @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2) 
    <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $followupIndents->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
    @endif
    @endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    function restoreIndent(indentId) {
        var isConfirmed = confirm("Are you sure you want restore this indent?");

        if(isConfirmed) {
            $.ajax({
                url: "{{ route('restore-followup-indents') }}",
                method: 'POST',
                data: {
                    indentId: indentId,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if(result.success == true) {
                        window.location.href = '/indents';
                    } else {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        }
    }
</script>