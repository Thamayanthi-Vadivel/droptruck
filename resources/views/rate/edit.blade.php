<button type="button">
    <a href="{{ route('rates.edit', ['rate' => $rate->id]) }}" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateRateModal">Update Rate</a>
</button>

<!-- Update Rate Modal -->
<div class="modal fade" id="updateRateModal" tabindex="-1" role="dialog" aria-labelledby="updateRateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateRateModalLabel">Update Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your update rate form here -->
                <form method="POST" action="{{ route('rates.update', ['rate' => $rate->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="rate">Rate:</label>
                        <input type="text" class="form-control" id="rate" name="rate" value="{{ $rate->rate }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Rate</button>
                </form>
            </div>
        </div>
    </div>
</div>