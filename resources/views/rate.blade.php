<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<a href="{{ route('rates.create', $indent->id) }}" class="text-decoration-none  text-dark" data-bs-toggle="modal" data-bs-target="#createRateModal{{ $indent->id }}"><i class="fa fa-bitcoin" style="color:green"></i></a>

<div class="modal fade" id="createRateModal{{ $indent->id }}" tabindex="-1" role="dialog" aria-labelledby="createRateModalLabel{{ $indent->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#F98917">
                <h5 class="modal-title" id="createRateModalLabel">Create Rate</h5>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('rates.store') }}">
                    @csrf
                    @if(auth()->user()->role_id === 4)
        <!-- Only include user_id if the user has role_id equal to 4 -->
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    @endif

    <input type="hidden" name="indent_id" value="{{ $indent->id }}">

                    <div class="form-group" style="display:none;" >
                        <label for="indent_id">Transport Name:</label>
                        <input type="text" class="form-control form-control-sm" id="customer_name" name="customer_name" value="{{ $indent->customer_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="enquiry_id">Enquiry No:</label>
                        <input type="text" class="form-control form-control-sm" id="enquiry_id" name="enquiry_id" value="{{ $indent->getUniqueENQNumber() }}" readonly>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="name">Transport Name:</label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ old('name') }}" >
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="rate">Rate:</label>
                        <input type="text" class="form-control form-control-sm" id="rate" name="rate" value="{{ old('rate') }}">
                    </div>

                    <div class="form-group mt-3">
                        <label for="remarks">Remarks:</label>
                        <textarea class="form-control  form-control-sm" id="remarks" name="remarks" rows="3"></textarea>
                    </div>

                    <!-- Other fields for rate creation here -->
                    <div class="mt-3 d-grid gap-3">
                        <button type="submit" class="btn dash1">Create Rate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
