@extends('layouts.sidebar')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header dash1 text-white">
            <h2 class="card-title">Edit Extra Cost</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('extra_costs.update', $extraCost->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <input type="hidden" name="indent_id" value="{{ $extraCost->indent_id }}">
                    <button class="btn btn-danger float-end mb-1">{{ $extraCost->indent->getUniqueENQNumber() }}</button>
                </div>

                <div class="form-group">
                    <label for="extra_cost_type">Extra Cost Type:</label>
                    <select name="extra_cost_type" class="form-select" required>
                        <option value="Labor" {{ $extraCost->extra_cost_type === 'Labor' ? 'selected' : '' }}>Labor</option>
                        <option value="Halt" {{ $extraCost->extra_cost_type === 'Halt' ? 'selected' : '' }}>Halt</option>
                        <option value="Over Load" {{ $extraCost->extra_cost_type === 'Over Load' ? 'selected' : '' }}>Over Load</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" name="amount" class="form-control" value="{{ $extraCost->amount }}" required>
                </div>

                <div class="form-group">
                    <label for="bill_copy">Bill Copy:</label>
                    <input type="file" name="bill_copy[]" class="form-control" multiple accept="image/*">
                </div>

                @if ($extraCost->bill_copy)
                    <div class="form-group">
                        <label>Existing Bill Copies:</label>
                        @foreach (explode(',', $extraCost->bill_copy) as $billCopy)
                            <div>
                                <a href="{{ asset($billCopy) }}" target="_blank"><img id="panCardPreview" src="{{ asset($billCopy) }}" alt="Bill Copy" style="width: 100px; height: 100px;"></a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label for="unloading_photo">Unloading Photo:</label>
                    <input type="file" name="unloading_photo" class="form-control" accept="image/*">
                </div>

                @if ($extraCost->unloading_photo)
                    <div class="form-group">
                        <!-- <label>Existing Unloading Photo:</label> -->
                        <div>
                            <a href="{{ asset('storage/' . $extraCost->unloading_photo) }}" target="_blank"><img id="panCardPreview" src="{{ asset($extraCost->unloading_photo) }}" alt="Bill Copy" style="max-width: 50%; height: 50px; width:50px;"></a>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="bill_copies">Bill Copies Information:</label>
                    <input type="file" name="bill_copies[]" class="form-control" multiple accept="image/*">
                </div>

                @if ($extraCost->bill_copies)
                    <div class="form-group">
                        <!-- <label>Existing Bill Copies Information:</label> -->
                        @foreach (explode(',', $extraCost->bill_copies) as $billCopy)
                            <div>
                                <a href="{{ asset($billCopy) }}" target="_blank"><img id="panCardPreview" src="{{ asset($billCopy) }}" alt="Bill Copy" style="max-width: 50%; height: 50px; width:50px;"></a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="d-grid mt-3">
                    <button type="submit" class="btn dash1">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
