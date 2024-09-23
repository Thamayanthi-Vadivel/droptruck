<!-- <button type="button" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"><i class="fa fa-trash" style="font-size:17px;"></i></button> -->

<a href="#" data-bs-toggle="modal" data-vehicle-id="{{ $vehicle->id }}" data-bs-target="#deleteConfirmationModal_{{ $vehicle->id }}"><i class="fa fa-trash" style="font-size:17px;"></i></a>

<div class="modal fade" id="deleteConfirmationModal_{{ $vehicle->id }}" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Truck?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <!-- Form for deleting the supplier -->
                <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>