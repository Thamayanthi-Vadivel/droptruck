<a href="{{ route('trucks.truck-type-edit', $types->id) }}" data-bs-toggle="modal" data-bs-target="#updateTruckTypeModal_{{ $types->id }}"><i class="fa fa-edit" style="font-size:17px;"></i></a>
<!-- Modal -->
<div class="modal fade" id="updateTruckTypeModal_{{ $types->id }}" tabindex="-1" role="dialog" aria-labelledby="updateTruckTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F98917;color:white;">
                <h5 class="modal-title" id="updateTruckTypeModalLabel">Update Truck TYpe</h5>
            </div>
            <div class="modal-body">
                {{-- Create form --}}
                {!! Form::open(['id' => 'form', 'route' => ['trucks.update', $types->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}

                <div class="form-group mb-3">
                    {!! Form::label('name', 'Truck Type') !!}
                    {!! Form::text('name', $types->name, ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit button --}}
                <div class="form-group d-grid gap-3">
                    {!! Form::submit('Update', ['class' => 'btn dash1']) !!}
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#form").submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting the traditional way
            submitForm();
        });
    });

    function submitForm() {
    var formData = new FormData($('#form')[0]);

    $.ajax({
        type: "POST",
        url: "/truck-type/store",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $("#message").html(response.success);
            $("#customerModal").modal('hide');
            window.location.reload();
        },
        error: function(xhr, status, error) {
            // Clear any previous error messages
            $('.text-danger').remove();

            if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Display validation errors
                var errors = xhr.responseJSON.errors;

                for (var key in errors) {
                    // Find the corresponding form field and append the error message
                    var inputField = $('[name="' + key + '"]');
                    var errorMessage = '<span class="text-danger">' + errors[key][0] + '</span>';
                    inputField.after(errorMessage);
                }
            } else {
                // Handle other errors
                alert("Error: " + xhr.responseText);
            }
        }
    });
}

</script>
