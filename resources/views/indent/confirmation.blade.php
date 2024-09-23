  @extends('layouts.sidebar')

  @section('content')
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <!-- Bootstrap JS bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Include Axios library -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <div class="d-flex justify-content-between gap-2 mt-1">
    <div class="fw-bolder  text-success">Confirmation Of Booking Truck</div>
  </div>
  <div class="d-flex">
    @if($indent->driver_rate == '0.00')
        <button type="button" class="btn btn-warning" style="margin-left:600px">
            <a href="/quoted" class="text-decoration-none text-dark"> Back</a>
        </button>
    @else
        <button type="button" class="btn btn-warning" style="margin-left:600px">
            <a href="/confirmed-locations" class="text-decoration-none text-dark"> Back</a>
        </button>
    @endif
</div>
  <input type="hidden" name="indent_id" id="indent_id" value="{{ $indent->id }}">

  <div class="card shadow border" style="margin:5% 20% 0 20%">
    <h5 class="card-header text-center mb-2 bg-warning">{{ $indent->customer_name }}</h5>
    <div class="card-body text-center">
      <p class="card-text"><strong class="label-width">Pickup Location:</strong><span>{{ ($indent->pickup_location_id) ? $indent->pickup_location_id : 'N/A' }}</span></p>
      <p class="card-text"><strong class="label-width">Drop Location:</strong><span> {{ ($indent->drop_location_id) ? $indent->drop_location_id : 'N/A' }}</span></p>
      <p class="card-text"><strong class="label-width">Truck Type:</strong><span>{{ $indent->truckType ? $indent->truckType->name : 'N/A' }}</span></p>
      <p class="card-text"><strong class="label-width">Body Type:</strong><span>{{ $indent->body_type }}</span></p>
      <p class="card-text"><strong class="label-width">Weight:</strong><span>{{ $indent->weight }}</span></p>
      <p class="card-text"><strong class="label-width">Material Type:</strong><span>{{ $indent->materialType ? $indent->materialType->name : 'N/A' }}</span></p>
      <p class="card-text"><strong class="label-width">Salesperson:</strong><span> {{ ($indent->user) ? $indent->user->name : '' }}</span></p>
      <p class="card-text"><strong class="label-width">Driver Rate:</strong>
        {{-- <span>    @if($indent->indentRate->isNotEmpty())

        {{ $indent->indentRate->sortBy('rate')->first()->rate }}
    @else
        N/A
    @endif</span> --}}
        @if($indent->driver_rate != '0.00')
          <span>  {{ $indent->driver_rate }} </span> <button type="button" class="btn delete-button" id="delete-driver-amount" data-indent-id="{{ $indent->id }}" fdprocessedid="q44b3c"><i class="fa fa-trash" style="font-size:8px;color:red" aria-hidden="true"></i></button>
          @php
          $rateId = $indent->indentRate->where('rate', $indent->driver_rate)->first()->id;

          @endphp
          <input type="hidden" name="drivers-rates" id="drivers-rates" value="{{ $indent->driver_rate_id }}">
        @else
           <select class="driver-rates" id="driver-rates">
                <option value="">Select Rates</option>
               @foreach($indentRates as $rate)
                <option value="{{ $rate->id }}"> {{ $rate->rate }} - {{ $rate->user->name }} </option>
            @endforeach

           </select> 
           <span class="driver-rates-error" style="color:red; display:none;">Select Driver Amount</span>
       @endif
      </p>
      <div class="d-flex">
      <button type="button" class="btn btn-info btn-sm fw-bolder" id="openRateModal" style="font-size: 8px; padding: 5px 10px;">
  Customer Rate
</button>


        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="rateModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="rateModalLabel">Customer Rate Form</h5>
              </div>
              <div class="modal-body">
                <form method="POST" action="{{ route('store.customer.rate', ['id' => $indent->id]) }}">
                  @csrf

                  <label for="rate">Rate:</label>
                  <input type="number" name="rate" value="{{ old('rate') }}" required>

                  <input type="hidden" name="indent_id" value="{{ $indent->id }}">
                  <input type="hidden" name="customer_rate" id="customer_rate" value="{{ ($indent->customerRate) ? $indent->customerRate->rate : '0' }}">
                  @error('rate')
                  <p style="color: red;">{{ $message }}</p>
                  @enderror

                  <button type="submit" class="btn btn-primary">Submit Rate</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <span id="customer_rates" style="margin-left:87px;">
          @if(optional($indent->customerRate)->rate)
          {{ $indent->customerRate->rate }}
          @else
          No customer rate found.
          @endif
        </span>
      </div>

      @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
      {{-- <a href="{{ route('confirm-to-trips', ['id' => $indent->id]) }}" onclick="confirmToTrips('{{ $indent->id }}'); return false;" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Win</a> --}}

      <a href="#" onclick="confirmToTrips('{{ $indent->id }}', $('#drivers-rates').val()); return false;" class="btn btn-success" style="font-size: 8px; padding: 5px 10px;">Win</a>

      <!-- Button trigger modal -->
      <button type="button" class="btn btn-danger" id="cancelIndent" style="font-size: 8px; padding: 5px 10px;"> 
        Cancel Indents
      </button>
      <!-- data-toggle="modal" data-target="#cancelModal" -->
      <!-- Modal -->
      <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{ route('cancel-indents-by-locations') }}" method="POST">
              @csrf
              <input type="hidden" name="pickup_location_id" value="{{ $pickupLocationId }}">
              <input type="hidden" name="drop_location_id" value="{{ $dropLocationId }}">
              <input type="hidden" name="indent_id" value="{{ $indent->id }}">
              <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Select Reason for Cancellation:</h5>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="reason">Select Reason for Cancellation:</label>
                  <select class="form-control" id="reason" name="reason">
                    <option value="">Select Cancel Reason</option>
                    <option value="Duplicate Enquiry">Duplicate Enquiry</option>
                    <option value="Found Alternate Vehicle">Found Alternate Vehicle</option>
                    <option value="Freight High">Freight High</option>
                    <option value="Just Enquiry">Just Enquiry</option>
                    <option value="Material not ready">Material not ready</option>
                    <option value="Not Responding">Not Responding</option>
                    <option value="Quote Delay">Quote Delay</option>
                    <option value="Quote Not Given">Quote Not Given</option>
                    <option value="Request Credit">Request Credit</option>
                    <option value="Trip Postponed">Trip Postponed</option>
                    <option value="Unavailability of vehicle">Unavailability of vehicle</option>
                    <option value="Will Confirm Later">Will Confirm Later</option>
                    <option value="Others">Others</option>
                    <option value="Followup">Followup</option>
                  </select>

                  <div class="row cancel_reasons" id="cancel_reasons_{{ $indent->id }}" style="display: none;">
                    <div class="form-group mt-1 col-md-12">
                        <label for="cancel_reason">Cancel Reason</label>
                        <input type="text" class="form-control form-control-sm cancel_reason" id="cancel_reason" name="cancel_reason">
                    </div>
                </div>
                <div class="row followups" id="followup_{{ $indent->id }}" style="display: none;">
                    <div class="form-group mt-1 col-md-12">
                        <label for="followup">Followup Date</label>
                        <input type="date" class="form-control form-control-sm" id="followup_date" name="followup_date">
                    </div>
                </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Bootstrap JS and jQuery (make sure to include jQuery before Bootstrap JS) -->
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



      @endif
    </div>
  </div>

  <style>
    p {
      text-align: left;
    }

    .label-width {
      width: 130px;
      /* Adjust the width according to your preference */
      display: inline-block;
    }

    span {
      display: inline-block;
      text-align: left;
      margin-left: 30px;
      /* Adjust the margin to add space between label and value */
    }

    .toast-error {
        color: red; /* Set the color you want */
    }
  </style>



  <script>
    function confirmToTrips(indentId, amountId) {
        console.log('indent--'+ indentId);
        console.log('amt-- '+amountId);
        var customerRate = $('#customer_rate').val();
        if (!amountId) {
            $('.driver-rates-error').css('display', 'block');
            return;
        } else {
            $('.driver-rates-error').css('display', 'none');
        }
        //var amount = $('#driver-rates').val();
        if(customerRate > 0) {
            // Perform an AJAX request to update the status
            //axios.post(`/confirm-to-trips/${indentId}`)
            axios.post(`/confirm-to-trips/${indentId}/${amountId}`)

                .then(response => {
                  // Handle success, e.g., show a success message

                  // Optionally, you can redirect to a new page or perform other actions.
                  // For example, to reload the current page:
                    window.location.href = '/confirmed-locations';
                })
                .catch(error => {
                  // Handle error, e.g., show an error message
                  console.error(error);
                });
        } else {
            $('#customer_rates').css("color", "red");
        }
      
    }

    $(document).on('change', '#driver-rates', function() {
        var amount = $(this).val();
        var indentId = $('#indent_id').val();
        
        if(amount) {
            $('.driver-rates-error').css('display', 'none');

            axios.post(`/confirm-driver-rate/${indentId}/${amount}`)
                .then(response => {
                  window.location.reload();
                })
                .catch(error => {
                  console.error(error);
            });
        } else {
            $('.driver-rates-error').css('display', 'block');
            return;
        }
    });

    $(document).on('click', '#cancelIndent', function() {
        var customerRate = $('#customer_rate').val();
        if (customerRate > 0) {
            $('#cancelModal').modal('show');
        } else {
            $('#customer_rates').css("color", "red");
        }
    });
  </script>

  <script>
    document.getElementById('openRateModal').addEventListener('click', function() {
      var myModal = new bootstrap.Modal(document.getElementById('rateModal'));
      myModal.show();
    });
  </script>

<script type="text/javascript">
      $(document).on('change', '#reason', function() {
        var indentId = $('#indent_id').val();
        var reason = $(this).val();
        
        if(reason === 'Others') {
           // alert('Others selected'); // Ensure this part runs
            $('.cancel_reasons').css('display', 'block');
            $('.cancel_reason').attr('required', true);
            $('.followup').attr('required', false);

        } else {
            $('.cancel_reasons').css('display', 'none'); // Hide it if it's not 'Others'
            $('.cancel_reason').attr('required', false);
            $('.followup').attr('required', false);
        }

        if(reason === 'Followup') {
           // alert('Others selected'); // Ensure this part runs
            $('.followups').css('display', 'block');
            $('.cancel_reason').attr('required', true);
            $('.cancel_reason').attr('required', false);
        } else {
            $('.followups').css('display', 'none'); // Hide it if it's not 'Others'
            $('.followup').attr('required', false);
            $('.cancel_reason').attr('required', false);
        }
    });
    
    $(document).on('click', '#delete-driver-amount', function() {
        var isConfirmed = confirm("Are you sure you want delete this driver amount?");
        var indentId = $(this).data('indent-id');

        if(isConfirmed) {
            $.ajax({
                url: "{{ route('delete-driver-amount') }}",
                method: 'POST',
                data: {
                    indentId: indentId,
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