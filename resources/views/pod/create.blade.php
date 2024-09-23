@extends('layouts.sidebar')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header dash1 text-white">
            <h2 class="card-title">Create POD</h2>
        </div>
        <div class="card-body">
            <form id="podForm" action="{{ route('pods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <input type="hidden" name="indent_id" value="{{ $indent->id }}">
                    <button class="btn btn-danger float-end mb-1">{{ $indent->getUniqueENQNumber() }}</button>
                </div>

                <div class="form-check mb-3" style="display: none;">
                    <input class="form-check-input" type="checkbox" id="togglePodForm">
                    <label class="form-check-label" for="togglePodForm">
                        Fill POD Form
                    </label>
                </div>
                 <div class="row">
                    <div class="form-group mt-1 col-md-6" id="source_type_option">
                        <label for="source_of_lead">Pod:*</label>
                        <select class="form-select form-select-sm" id="pod_type" name="pod_type" required>
                            <option value="select">select</option>
                            <option value="1">Soft Copy</option>
                            <option value="2">Hard Copy</option>
                            <option value="3">Not Required</option>
                        </select>
                    </div>
                </div>
                <br>
                <div id="podFormFields" style="display: none;">
                    <div class="form-group col-lg-3" id="courier_no" style="display: none;">
                        <label for="courier_receipt_no">Courier Receipt No:*</label>
                        <input type="text" name="courier_receipt_no" id="courier_receipt_no" class="form-control">
                    </div>

                    <div class="form-group" id="pod_soft_copy" style="display: none;">
                        <label for="pod_soft_copy">Pod Soft Copy: *</label>
                        <input type="file" name="pod_soft_copy" id="pod_soft_copy_photo" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group" id="pod_hard_copy" style="display: none;">
                        <label for="pod_courier">Pod Courier:*</label>
                        <input type="file" name="pod_courier" id="pod_courier" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group" id="pod_courier_copy" style="display: none;">
                        <label for="pod_courier">Pod Receipt:*</label>
                        <input type="file" name="pod_courier_photo" id="pod_courier_photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="d-dlex mt-3">
                        <button type="submit" name="submit_with_data" value="true" class="btn btn-primary mt-3">Submit</button>
                        <!-- <button type="submit" name="submit_without_data" value="true" class="btn btn-danger mt-3">Submit without Data</button> -->
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    document.getElementById('togglePodForm').addEventListener('change', function() {
        var podFormFields = document.getElementById('podFormFields');
        podFormFields.style.display = this.checked ? 'block' : 'none';
    });

    $(document).on('change', '#pod_type', function() {
        var podType = $(this).val();
        
        if(podType == 1) {
            $("#podFormFields").show();
            $("#courier_no").hide();
            $("#pod_soft_copy").show();
            $("#pod_hard_copy").hide();
            $("#pod_courier_copy").hide();

            $("#pod_courier").attr('required', false);
            $("#courier_receipt_no").attr('required', false);
            $("#pod_courier_photo").attr('required', false);
            $("#pod_soft_copy_photo").attr('required', true);
        } else if(podType == 2) {
            $("#podFormFields").show();
            $("#courier_no").show();
            $("#pod_soft_copy").hide();
            $("#pod_hard_copy").show();
            $("#pod_courier_copy").show();

            $("#courier_receipt_no").attr('required', true);
            $("#pod_soft_copy").attr('required', false);
            $("#pod_courier").attr('required', true);
            $("#pod_courier_photo").attr('required', true);
        } else {
            $("#podFormFields").hide();
            $("#courier_no").hide();
            $("#pod_soft_copy").hide();
            $("#pod_hard_copy").hide();
            $("#pod_courier_copy").hide();

            $("#courier_receipt_no").attr('required', false);
            $("#pod_soft_copy").attr('required', false);
            $("#pod_courier").attr('required', false);
            $("#pod_courier_photo").attr('required', false);
        }
    });
</script>
@endsection