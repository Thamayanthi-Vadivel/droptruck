@extends('layouts.sidebar')
@section('content')
<div class="d-flex justify-content-end gap-2">
    <div>
        <form class="form-inline mt-3" type="get" action="{{url('/search/employee')}}">
            <input class="form-control" name="query" type="search" placeholder="search...">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
    </div>
    <div>
        <button type="button" class="btn dash1 mt-3">
            <a href="{{ route('employees.create') }}" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#exampleModal"> Add Employee</a>
        </button>
         <button class="btn btn-primary rounded mt-3">
            <a href="{{ route('export.users') }}">Download Users</a>
        </button>
        @include('employees.create')
    </div>
</div>
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<table class="table table-bordered text-start">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Designation</th>
            <th>Role</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody style="background-color:#D9D9D9;">
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->contact }}</td>
            <td>{{ $user->designation }}</td>
            <td>{{ $user->role->type }}</td>
            <td>
                <button class="btn {{ $user->status ? 'btn-primary' : 'btn-danger' }}"  id="change-employee-status" data-id="{{ $user->id }}" data-status="{{ $user->status }}">
                    {{ $user->status ? 'Active' : 'Inactive' }}
                </button>
            </td>

            <!-- Display status -->
            <td>{{ $user->remarks }}</td>
            <td>
                <a href="{{ route('employees.view', ['id' => $user->id]) }}">
                    <i class="fa fa-eye" style="font-size:17px"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center p-0 pagination-sm">
    {{ $users->links('pagination::bootstrap-5', ['class' => 'pagination-sm']) }}
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
$(document).on('click', '#change-employee-status', function() {
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
                url: "{{ route('change-employee-status') }}",
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