@extends('layouts.sidebar')
@section('content')
<div class="d-flex justify-content-end gap-2">
    <div>
        <form type="get" action="{{url('/search/customer')}}">
            <input class="form-control mt-3" name="query" type="search" placeholder="search...">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
    </div>
    <div>
        <button type="button" class="btn dash1 mt-3">
            <a href="{{ route('customers.create') }}" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#customerModal"> Create Customer</a>
        </button>
        @include('customers.create')
    </div>
    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
        <div>
            <a href="{{ route('customer-export') }}" class="btn btn-sm float-end btn-success mt-3">Customer Report</a>
        </div>
    @endif
</div>



<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sales Name</th>
                <th>Customer Name</th>
                <th>Company Name</th>
                <th>Contact Number</th>
                <th>Source of Lead</th>
                <th>Onboard Date</th>
                <th>First Confirmed Date</th>
                <th>Last Confirmed Date</th>
                <th>No. of Enquiry</th>
                <th>No. of Confirmed Trips</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                @php

                    $indentCount = DB::table('indents')->where('number_1', $customer->contact_number)->count();
                    $confirmedTripsCount = DB::table('indents')->where('number_1', $customer->contact_number)->whereNotNull('confirmed_date')->count();
                    $firstTrip = DB::table('indents')->where('number_1', $customer->contact_number)->whereNotNull('confirmed_date')->orderBy('confirmed_date', 'asc')->first();
                    $salesUser = DB::table('indents')->where('number_1', $customer->contact_number)->first();

                    $firstTripDate = 'N/A';
                    $lastTripDate = 'N/A';
                    $salePersonName = 'N/A';
                    $onBoardDate = 'N/A';

                    if($salesUser) {
                        $salePerson = DB::table('users')->where('id', $salesUser->user_id)->first();
                        if ($salePerson && $salePerson->name) {
                            try {
                                $salePersonName = $salePerson->name;
                            } catch (Exception $e) {
                                $salePersonName = 'N/A';
                            }
                        }
                    }
                    
                    if ($firstTrip && $firstTrip->confirmed_date) {
                        try {
                            $date = new DateTime($firstTrip->confirmed_date);
                            $firstTripDate = $date->format('Y-m-d');
                        } catch (Exception $e) {
                            $firstTripDate = 'N/A';
                        }
                    }

                    $lastTrip = DB::table('indents')->where('number_1', $customer->contact_number)->whereNotNull('confirmed_date')->orderBy('confirmed_date', 'desc')->first();
                    if ($lastTrip && $lastTrip->confirmed_date) {
                        try {
                            $date = new DateTime($lastTrip->confirmed_date);
                            $lastTripDate = $date->format('Y-m-d');
                        } catch (Exception $e) {
                            $lastTripDate = 'N/A';
                        }
                    }

                    if($salesUser && $salesUser->created_at) {
                        try {
                            $date = new DateTime($salesUser->created_at);
                            $onBoardDate = $date->format('Y-m-d');
                        } catch (Exception $e) {
                            $onBoardDate = 'N/A';
                        }
                    }
                @endphp
            <tr>
                <td>{{ $salePersonName }}</td>
                <td>{{ $customer->customer_name }}</td>
                <td>{{ $customer->company_name }}</td>
                <td>{{ $customer->contact_number }}</td>
                <td>{{ ($customer->lead_source) ? $customer->lead_source : 'N/A' }}</td>
                <td>{{ $onBoardDate }}</td>
                <td>{{ $firstTripDate }}</td>
                <td>{{ $lastTripDate }}</td>
                <td>{{ $indentCount }}</td>
                <td>{{ $confirmedTripsCount }}</td>
                <td>
                    <button class="btn {{ $customer->status ? 'btn-primary' : 'btn-danger' }}" id="change-customer-status" data-id="{{ $customer->id }}" data-status="{{ $customer->status }}">
                    {{ $customer->status ? 'Active' : 'Inactive' }}
                </button>                
            </td>
                <td>{{$customer->remarks}}</td>
                <td class="d-flex">
                    <div>@include('customers.edit')</div>
                    <a href="{{ route('customers.show', $customer->id) }}">
                        <i class="fa fa-eye" style="font-size:17px"></i>
                    </a>
                    <div>@include('customers.delete')</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $customers->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
$(document).on('click', '#change-customer-status', function() {
        var isConfirmed = confirm("Are you sure you want to change status?");
        var userId = $(this).data('id');
        var userStatus = $(this).data('status');
       
        if(userStatus == 1) {
            var status = 0;
        } else {
            var status = 1;
        }
        if(isConfirmed) {
            $.ajax({
                url: "{{ route('change-customer-status') }}",
                method: 'POST',
                data: {
                    userId: userId,
                    status: status,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if(result.success == true) {
                       window.location.reload();
                    } else {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error(error.responseText);
                }
            });
        }
    });
</script>
@endsection