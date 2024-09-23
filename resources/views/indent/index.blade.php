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

    <?php //echo '<pre>'; print_r(auth()->user()); exit; ?>
    <div>
        <h2 class="btn btn-primary text-white fw-bolder float-end mt-1">User: {{ auth()->user()->name }}</h2>
    </div>
    <div class="d-flex justify-content-between">
        <div class="m-3">
            <a href="{{ route('indents.index') }}" class="btn btn-warning custom-active" style="font-size: 12px; padding: 5px 10px;position: relative;">Unquoted
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
             @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2) 
            {{ $indentsCount->count() }}
            @else 
            {{ $indents->count() }}
            @endif
    </span>
        </a>
            <a href="{{ route('fetch-last-two-details') }}" class="btn btn-warning " style="font-size: 12px; padding: 5px 10px;position: relative;">Quoted<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $quotedIndents }}
    </span></a>
            <a href="{{ route('confirmed_locations')}}" class="btn btn-warning" style="font-size: 12px; padding: 5px 10px;position: relative;">Confirmed<span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $confirmedIndents }}
    </span></a>
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
                 <th class="bg-gradient-info text-light">Payment Terms</th>
                <th class="bg-gradient-info text-light">Sales Remarks</th>
                @if(auth()->user()->role_id != 4)
                    <th class="bg-gradient-info text-light">Source of Lead</th>
                @endif
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <th class="bg-gradient-info text-light">Salesperson</th>
                @endif
                <th class="bg-gradient-info text-light">Created By</th>
                <th class="bg-gradient-info text-light">Required Date</th>
                <th class="bg-gradient-info text-light">Created Date</th>
                <th class="bg-gradient-info text-light">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($indents as $indent)
                @php 
                if($indent->new_material_type == null) {
                    $materialType = ($indent->materialType) ? $indent->materialType->name : 'N/A';
                } else {
                    $materialType = $indent->new_material_type;
                }

                if($indent->new_body_type == null) {
                    $bodyType = ($indent->body_type) ? $indent->body_type : 'N/A';
                } else {
                    $bodyType = $indent->new_body_type;
                }

                if($indent->new_truck_type == null) {
                    $truckType = ($indent->truckType) ? $indent->truckType->name : 'N/A';
                } else {
                    $truckType = $indent->new_truck_type;
                }

                if($indent->new_source_type == null) {
                    $sourceType = $indent->source_of_lead;
                } else {
                    $sourceType = $indent->new_source_type;
                }
                @endphp 
            <tr>
                <td>{{ $indent->getUniqueENQNumber() }}</td>
                @if(auth()->user()->role_id != 4)
                <td>{{ $indent->company_name }}</td>
                <td>{{ $indent->customer_name }}</td>
                <td>{{ $indent->number_1 }}</td>
                @endif
                <td>{{ $indent->pickup_location_id ? $indent->pickup_location_id : 'N/A' }}</td>
                <td>{{ $indent->drop_location_id ? $indent->drop_location_id : 'N/A' }}</td>

                <td>{{ $truckType }}</td>
                <td>{{ $bodyType }}</td>
                <td>{{ $indent->weight }} {{ $indent->weight_unit }}</td>

                <td>{{ $materialType }}</td>

                <td>{{ $indent->pod_soft_hard_copy }}</td>
                <td>{{ $indent->payment_terms }}</td>
                <td>{{ $indent->remarks }}</td>
                @if(auth()->user()->role_id != 4)
                    <td>{{ $sourceType }}</td>
                @endif
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <td>{{ ($indent->user) ? $indent->user->name : 'N/A' }}</td>
                @endif
                <td>{{ ($indent->createdUser) ? $indent->createdUser->name .' - '.$indent->createdUser->designation : '' }}</td>
                <td>{{ $indent->required_date }}</td>
                <td>{{ $indent->created_at }}</td>
                <td class="d-flex">
                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
                    <div>@include('indent.delete')</div>
                    <a href="{{ route('indents.show', ['indent' => $indent->id, 'page' => '1']) }}" class="btn"><i class="fa fa-eye" style="font-size:8px;color:darkblue"></i></a>
                    <div>@include('indent.edit')</div>
                    @endif
                    @if(auth()->user()->role_id == 4)
                    <div>@include('rate')</div>
                    @endif
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
     @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
    <div class="d-flex justify-content-center p-0 pagination-sm">
    {{ $indents->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    
</div>
@endif
<script>
    setTimeout(function(){
       location.reload();
    }, 600000); // 10000 milliseconds = 10 seconds
</script>
    @endsection

