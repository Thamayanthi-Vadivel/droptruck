@extends('layouts.sidebar')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header dash1 text-white">
            <h2 class="card-title">Edit POD</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('pods.update', $pod->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="courier_receipt_no">Courier Receipt No:</label>
                    <input type="text" name="courier_receipt_no" class="form-control" value="{{ $pod->courier_receipt_no }}" required>
                </div>

                <div class="form-group">
                    <label for="pod_soft_copy">Pod Soft Copy:</label>
                    <input type="file" name="pod_soft_copy" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="pod_courier">Pod Courier:</label>
                    <input type="file" name="pod_courier" class="form-control" accept="image/*">
                </div>
<div class="d-grid mt-2">
                <button type="submit" class="btn dash1">Update</button>
</div>
            </form>
        </div>
    </div>
</div>
@endsection
