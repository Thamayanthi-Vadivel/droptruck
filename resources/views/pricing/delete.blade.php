<!-- <button type="button" class="btn btn-danger pe-3" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">Delete</button> -->

<a href="#" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal_{{ $pricing->id }}"><i class="fa fa-trash" style="font-size:17px;"></i></a>

<div class="modal fade" id="deleteConfirmationModal_{{ $pricing->id }}" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this pricing?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <!-- Form for deleting the supplier -->
                <form action="{{ route('pricings.destroy', $pricing->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>