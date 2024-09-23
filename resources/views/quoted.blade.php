@extends('layouts.sidebar')

@section('content')

@php 
use App\Models\Indent;
use App\Models\Rate;
@endphp
<style>
        th {
            color: blueviolet;
        }

        .btn-warning.custom-active {
            background: linear-gradient(135deg, #007bff, #8a2be2);
            color: #fff;
            border: #8a2be2;
        }

        .small-input {
            width: 150px;
            font-size: 0.8rem;
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
            <a href="{{ route('fetch-last-two-details') }}" class="btn btn-warning custom-active" style="font-size: 12px; padding: 5px 10px;position: relative;">Quoted
            <span class="badge badge-primary circle-badge text-light" id="canceledIndentsCount" style="position: absolute; top: -10px; right: -10px; background: linear-gradient(45deg, #F31559, #F6635C);">
            {{ $indentsCount }}
    </span>
        </a>
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
<div id="quoted-content">
<table class="table table-bordered table-striped table-hover" style="font-size:8px;">
        <thead>
            <tr>
                <th class="bg-gradient-info text-light">ENQ Number</th>
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
                <th class="bg-gradient-info text-light">Rate L1</th>
                <th class="bg-gradient-info text-light">L1 Remarks</th>
                <th class="bg-gradient-info text-light">Rate L2</th>
                <th class="bg-gradient-info text-light">L2 Remarks</th>
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                <th class="bg-gradient-info text-light">Sales Person</th>
                @endif
                
                <th class="bg-gradient-info text-light">Created By</th>
                <th class="bg-gradient-info text-light">Remarks</th>
                <th class="bg-gradient-info text-light">Created Date</th>
                <th class="bg-gradient-info text-light">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php //echo 'sds<pre>'; print_r($indents); exit; ?>
            @foreach($indents as $indent)
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
                @endphp 
            <tr>
                <td>{{ $indent->getUniqueENQNumber() }}</td>
                @if(auth()->user()->role_id != 4)
                <td>{{ $indent->company_name }}</td>
                <td>{{ $indent->customer_name }}</td>
                <td>{{ $indent->number_1 }}</td>
                @endif
                <td> {{ $indent->pickup_location_id }} </td>
                <td> {{ $indent->drop_location_id }} </td>
                <td>{{ $truckType }}</td>
                <td>{{ $bodyType }}</td>
                <td>{{ $indent->weight }} {{ $indent->weight_unit }}</td>
                <td>{{ $materialType }}</td>
                <td>{{ $indent->pod_soft_hard_copy }}</td>
                <td>{{ $indent->payment_terms }}</td>
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
                <td>
                    @if($indent->indentRate->isNotEmpty())
                        {{ $indent->indentRate->sortBy('rate')->first()->rate }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($indent->indentRate->isNotEmpty())
                        {{ $indent->indentRate->sortBy('rate')->first()->remarks }}
                    @else
                        N/A
                    @endif
                </td>
                        @php
                            $secondMinRate = $indent->indentRate() // Assuming 'indentRate' is the relationship method
                                ->where('is_confirmed_rate', 0) // Add any additional conditions as needed
                                ->orderBy('rate', 'asc')
                                ->skip(1) // Skip the first rate (minimum rate)
                                ->take(1) // Take the first rate from the remaining collection
                                ->first();
                                //->pluck('rate') // Pluck the 'rate' attribute
                                
                                
                            $pickupLocationId = optional($indent->pickupLocation)->id;
                            $dropLocationId = optional($indent->dropLocation)->id;
                            $locationCombination = $pickupLocationId . '-' . $dropLocationId;
                         
                             $secondLeastRate = $secondLeastRateAmounts;
                             $secondLeastRate = isset($secondLeastRateAmounts[$locationCombination])
                                ? $secondLeastRateAmounts[$locationCombination]
                                : null;
                        @endphp
                <td>
                    @if ($secondMinRate !== null)
                        {{ $secondMinRate->rate }}
                    @else
                        N/A
                    @endif
                </td>
                
                <td>
                    @if ($secondMinRate !== null)
                        {{ $secondMinRate->remarks }}
                    @else
                        N/A
                    @endif
                </td>

                @endif

                @if(auth()->user()->role_id === 4)
                <td>
                     <!-- $leastRateForLoggedInSupplier = Rate::leftJoin('indents', 'indents.id', '=', 'rates.indent_id')
                    ->where('indents.status', 0)
                    ->where('rates.user_id', $user->id)
                    ->orderBy('rate')->first(); -->
                    @php
                  
                    $leastRateForLoggedInSupplier = $indent->indentRate->where('user_id', $user->id)->sortBy('rate')->first();
                   
                    
                    @endphp

                    @if($leastRateForLoggedInSupplier)
                    {{ $leastRateForLoggedInSupplier->rate }}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if($leastRateForLoggedInSupplier)
                        {{ $leastRateForLoggedInSupplier->remarks }}
                    @else
                        N/A
                    @endif
                </td>
                    @php
                        $secondLeastRateForLoggedInSupplier = $indent->indentRate
                        ->where('user_id', $user->id)
                        ->sortBy('rate')
                        ->skip(1) // Skip the first rate
                        ->first();
                    @endphp
                <td>
                    @if($secondLeastRateForLoggedInSupplier)
                    {{($secondLeastRateForLoggedInSupplier->rate) }}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if($secondLeastRateForLoggedInSupplier)
                        {{ $secondLeastRateForLoggedInSupplier->remarks }}
                    @else
                        N/A
                    @endif
                </td>
                <!-- <td>
                    @if($secondLeastRateAmounts)
                    {{($secondLeastRateAmounts) }}
                    @else
                    N/A
                    @endif
                </td> -->
                @endif
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                   <td>{{ $indent->user ? $indent->user->name : 'N/A' }}</td>
                @endif
                <td>{{ ($indent->createdUser) ? $indent->createdUser->name .' - '.$indent->createdUser->designation : '' }}</td>
                <td>{{ $indent->remarks }}</td>
                <td>{{ $indent->created_at }}</td>

                <td class="d-flex">
                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
                        <a href="{{ route('indents.show', ['indent' => $indent->id, 'page' => '1']) }}" class="btn"><i class="fa fa-eye" style="font-size:8px;color:blue"></i></a>
                        <!-- <a href="{{ route('showIndentDetails')}}" class="btn"><i class="fa fa-info-circle" style="font-size:8px;color:darkorange"></i></a>
                        <a href="{{ route('recyclebin.index') }} " class="btn"><i class="fa fa-database" style="font-size:8px;color:darkorchid"></i></a> -->
                        @if($indent->status != 1)
                        <a class="btn" href="{{ route('indents.confirm', ['id' => $indent->id]) }}">
                            <i class="fa fa-check-circle" style="font-size:8px;color:green"></i>
                        </a>
                        @endif
                    @endif
                    
                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                        <div>
                            @include('indent.edit')
                        </div>
                    @endif
                    @if(auth()->user()->role_id == 4)
                    <div>@include('rate')</div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $indents->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
</div>
<script>
    setTimeout(function(){
       location.reload();
    }, 600000); // 10000 milliseconds = 10 seconds
</script>
<script>
    function cancelTripAndRefresh(id) {
        fetch(`/cancel-trips/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('quoted-content').innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
    }
</script>
@endsection