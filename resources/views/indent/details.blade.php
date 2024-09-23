<!-- resources/views/indents/index.blade.php -->

@extends('layouts.sidebar')

@section('content')

<!-- <div class="d-flex justify-content-between mt-3 bg-light"> -->
<div class="mt-2">
    <a class="btn dash1 float-end mb-2" href="{{ route('fetch-last-two-details') }}">Quoted</a>
    <!-- </div> -->
    <!-- <div class="mt-4">
    <form method="get" action="{{ route('showIndentDetails') }}">
    @csrf

    <input type="text" name="pickup_location" id="pickup_location" placeholder="Select Pickup Location" value="{{ $selectedPickupLocationId }}">
    <input type="text" name="drop_location" id="drop_location" placeholder="Select Drop Location" value="{{ $selectedDropLocationId }}">

    <button type="submit" class="btn dash1">Submit</button>
</form>
    </div> -->
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Salesperson</th>
            <th>Rate</th>
            <!-- <th>Remarks</th> -->
        </tr>
    </thead>
    <tbody>
    @foreach ($indents as $indent)

    <tr>
        <td>{{$indent->company_name}}</td>
        <td>{{ $indent->user->name }}</td>
        <td>
            @if($indent->indentRate->isNotEmpty())
                {{ $indent->indentRate->sortBy('rate')->first()->rate }}
            @else
                N/A
            @endif
        </td>
        <!-- <td>
            {{-- Loop through all rates associated with the indent --}}
            @foreach ($indent->indentRate as $rate)
                {{ $rate->remarks }}
            @endforeach
        </td> -->
    </tr>
@endforeach

    </tbody>
</table>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- <script>
$( "#pickup_location" ).autocomplete({
    source: function(request, response) {
        $.ajax({
            url: "{{ route('locations.autocomplete') }}",
            dataType: "json",
            data: {
                term: request.term
            },
            success: function(data) {
                response(data.map(location => ({ label: location.district })));
            }
        });
    },
    minLength: 2
});

// Autocomplete for drop location
$( "#drop_location" ).autocomplete({
    source: function(request, response) {
        $.ajax({
            url: "{{ route('locations.autocomplete') }}",
            dataType: "json",
            data: {
                term: request.term
            },
            success: function(data) {
                response(data.map(location => ({  label: location.district })));
            }
        });
    },
    minLength: 2
});

</script> -->

@endsection
