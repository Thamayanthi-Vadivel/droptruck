@extends('layouts.sidebar')

@section('content')
    <div class="container">
        <h2>Create Material</h2>
        <form method="POST" action="{{ route('materials.store') }}" id="createMaterialForm">
            @csrf
            <div class="form-group">
                <label for="name">Material Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="button" id="submitMaterialForm" class="btn btn-primary">Create Material</button>
        </form>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#submitMaterialForm').on('click', function (e) {
                e.preventDefault();

                var formData = $('#createMaterialForm').serialize();

                $.ajax({
                    url: "{{ route('materials.store') }}",
                    type: "POST",   
                    data: formData,
                    success: function (response) {
                        // Handle success, e.g., show a success message
                        console.log(response);
                        // Additional actions as needed
                    },
                    error: function (xhr) {
                        // Handle errors, e.g., show an error message
                        console.error(xhr.responseText);
                        // Additional actions as needed
                    }
                });
            });
        });
    </script>
@endsection
