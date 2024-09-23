<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F98917;color:white;">
                <h5 class="modal-title" id="customerModalLabel">Add Truck TYpe</h5>
            </div>
            <div class="modal-body">
                {{-- Create form --}}
                {!! Form::open(['id' => 'form', 'route' => 'trucks.store', 'enctype' => 'multipart/form-data']) !!}

                <div class="form-group mb-3">
                    {!! Form::label('name', 'Truck Type') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit button --}}
                <div class="form-group d-grid gap-3">
                    {!! Form::submit('Create', ['class' => 'btn dash1']) !!}
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

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
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Display validation errors
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "Validation Error: ";
                    for (var key in errors) {
                        errorMessage += errors[key][0] + "<br>";
                    }
                    alert(errorMessage);
                } else {
                    // Handle other errors
                    alert("Error: " + xhr.responseText);
                }
            }
        });
    }
</script>
