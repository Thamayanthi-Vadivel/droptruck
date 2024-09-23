<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F98917;color:white;">
                <h5 class="modal-title" id="customerModalLabel">Add Customer</h5>
            </div>
            <div class="modal-body">
                {{-- Create form --}}
                {!! Form::open(['id' => 'form', 'route' => 'customers.store', 'enctype' => 'multipart/form-data']) !!}

                <div class="form-group mb-3">
                    {!! Form::label('customer_name', 'Customer Name') !!}
                    {!! Form::text('customer_name', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                    @error('customer_name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('company_name', 'Company Name') !!}
                    {!! Form::text('company_name', null, ['class' => 'form-control']) !!}

                    @error('company_name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('contact_number', 'Contact Number') !!}
                    {!! Form::text('contact_number', null, ['class' => 'form-control']) !!}

                    @error('contact_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('address', 'Address') !!}
                    {!! Form::text('address', null, ['class' => 'form-control']) !!}

                    @error('address')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- <div class="form-group mb-3">
                    {!! Form::label('gst_number', 'GST Number') !!}
                    {!! Form::text('gst_number', null, ['class' => 'form-control']) !!}

                    @error('gst_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}

                <div class="form-group mb-3">
                    {!! Form::label('lead_source', 'Lead Source') !!}
                    {!! Form::select('lead_source', [
                        'select' => 'Select',
                        'Old data' => 'Old data',
                        'Telecalling' => 'Telecalling',
                        'WhatsApp Blast' => 'WhatsApp Blast',
                        'Justdial' => 'Justdial',
                        'Referal' => 'Referal',
                        'SMS' => 'SMS',
                        'Webpage' => 'Webpage',
                        'Field visit' => 'Field visit',
                        'Trade Centre' => 'Trade Centre',
                        'Sulekha' => 'Sulekha',
                        'Others' => 'Others',
                    ], 
                    null, 
                    ['class' => 'form-select form-select-sm', 'id' => 'vehicle_type', 'fdprocessedid' => 'hnprge']
                ) !!}
                    <!-- {!! Form::text('lead_source', null, ['class' => 'form-control']) !!} -->

                    @error('lead_source')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', [
                        'select' => 'Select',
                        '1' => 'Active',
                        '2' => 'Inactive',
                    ], 
                    null, 
                    ['class' => 'form-select form-select-sm', 'id' => 'status', 'fdprocessedid' => 'hnprge']
                ) !!}
                </div>
                
                {{-- File upload fields --}}
                <div class="form-group mb-3">
                    {!! Form::label('business_card[]', 'Business Card Images') !!}
                    {!! Form::file('business_card[]', ['class' => 'form-control', 'multiple' => true, 'accept' => 'image/jpeg,image/png,application/pdf']) !!}

                    @error('business_card.*')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('gst_document', 'GST') !!}
                    {!! Form::file('gst_document', ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,application/pdf']) !!}

                    @error('gst_document')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('company_name_board', 'Company Name Board') !!}
                    {!! Form::file('company_name_board', ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,application/pdf']) !!}

                    @error('company_name_board')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="remarks">Remarks:</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>

                    @error('remarks')
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
            url: "/customers/create",
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
