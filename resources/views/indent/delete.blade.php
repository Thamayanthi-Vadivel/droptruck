<style>
    .cancel_reasonsss {
        display: none;
    }
</style>

<button type="button" class="btn delete-button" data-indent-id="{{ $indent->id }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal_{{ $indent->id }}">
    <i class="fa fa-trash" style="font-size:8px;color:red" aria-hidden="true"></i>
</button>

<div class="modal fade" id="deleteConfirmationModal_{{ $indent->id }}" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('indents.destroy', $indent->id) }}" method="POST">
            <!-- @method('DELETE') -->
            @method('POST')
            @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="cancelModalLabel">Select Reason for Delete</h5>
          </div>
          <input type="hidden" class="form-control" id="indent_id" value="{{ $indent->id }}">
          <div class="modal-body">
            <div class="form-group">
              <label for="cancelReason">Select Reason for Delete</label>
              <select class="form-control" id="reason" name="reason" fdprocessedid="gqtna">
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
            <button type="submit" class="btn btn-danger">Confirm Delete</button>
          </div>
        </form>
      </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
</script>