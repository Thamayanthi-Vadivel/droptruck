@extends('layouts.sidebar')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end gap-2">
        <div>
            <form type="get" action="{{url('/search/supplier')}}">
                <input class="form-control mt-3" name="query" type="search" placeholder="search...">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
        </div>
        <div>
            <button type="button" class="btn dash1 mt-3" fdprocessedid="ucoki">
                <a href="/suppliers/create" class="text-decoration-none text-dark"> Add Supplier</a>
            </button>
        </div>
        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
            <div>
                <a href="{{ route('supplier-export') }}" class="btn btn-sm float-end btn-success mt-3">Supplier Report</a>
            </div>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Vendor Name</th>
                    <th>Vendor Type</th>
                    <th>Company Name</th>
                    <th>Contact Number</th>
                    <th>Pan Card</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $supplier->supplier_name }}</td>
                    <td>{{ $supplier->supplier_type }}</td>
                    <td>{{ $supplier->company_name }}</td>
                    <td>{{ $supplier->contact_number }}</td>
                    <td>{{ $supplier->pan_card_number }}</td>
                    <td>
                        <button class="btn {{ $supplier->status ? 'btn-primary' : 'btn-danger' }}" id="change-supplier-status" data-id="{{ $supplier->id }}" data-status="{{ $supplier->status }}">
                            {{ $supplier->status ? 'Active' : 'Inactive' }}
                        </button>                
                    </td>
                    <td>{{ $supplier->remarks }}</td>
                    <td class="d-flex">
                        <div>@include('suppliers.edit')</div>
                        <a href="{{ route('suppliers.show', $supplier->id) }}">
                            <i class="fa fa-eye" style="font-size:17px"></i>
                        </a>
                        <div>@include('suppliers.delete')</div>
                    </td>
                </tr>
                @php
                    $i++;
                @endphp
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center p-0 pagination-sm">
        {{ $suppliers->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
    </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
$(document).on('click', '#change-supplier-status', function() {
        var isConfirmed = confirm("Are you sure you want to change status?");
        var supplierId = $(this).data('id');
        var supplierStatus = $(this).data('status');
       
        if(supplierStatus == 1) {
            var status = 0;
        } else {
            var status = 1;
        }
        if(isConfirmed) {
            $.ajax({
                url: "{{ route('change-supplier-status') }}",
                method: 'POST',
                data: {
                    supplierId: supplierId,
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