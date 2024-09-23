<div class="d-flex">
    <button type="button" class="btn btn-info fw-bolder" id="openRateModal">
        Customer Rate
    </button>

    <!-- Modal -->
    <div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="rateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateModalLabel">Edit Customer Rate</h5>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('customer-rate.update', ['indent_id' => $indent->id]) }}">
                        @csrf
                        @method('PUT')
                        <label for="rate">Rate:</label>
                        <input type="number" name="rate" value="{{ optional($indent->customerRate)->rate }}" required>

                        <input type="hidden" name="indent_id" value="{{ $indent->id }}">

                        @error('rate')
                        <p style="color: red;">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="btn btn-primary">Update Rate</button>
                    </form>


                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $("#openRateModal").click(function() {
            $("#rateModal").modal("show");
        });
    });
</script>