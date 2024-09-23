<!-- Button to trigger the modal -->
<!-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#updateCustomerModal">
    Update
</button> -->

<a href="#" data-bs-toggle="modal" data-bs-target="#updateCustomerModal_{{ $customer->id }}"><i class="fa fa-edit" style="font-size:17px;"></i></a>

<!-- Modal -->
<div class="modal fade" id="updateCustomerModal_{{ $customer->id }}" tabindex="-1" aria-labelledby="updateCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #F98917">
                <h5 class="modal-title" id="updateCustomerModalLabel">Update Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::model($customer, ['route' => ['customers.update', $customer->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}

                <!-- Your form fields and labels go here -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Customer Name -->
                        <div class="form-group mb-3">
                            {!! Form::label('customer_name', 'Customer Name') !!}
                            {!! Form::text('customer_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <!-- Company Name -->
                        <div class="form-group mb-3">
                            {!! Form::label('company_name', 'Company Name') !!}
                            {!! Form::text('company_name', null, ['class' => 'form-control']) !!}
                        </div>
                        <!-- Contact Number -->
                        <div class="form-group mb-3">
                            {!! Form::label('contact_number', 'Contact Number') !!}
                            {!! Form::text('contact_number', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <!-- Address -->
                        <div class="form-group mb-3">
                            {!! Form::label('address', 'Address') !!}
                            {!! Form::text('address', null, ['class' => 'form-control']) !!}
                        </div>
                        <!-- GST Number -->
                        {{-- <div class="form-group mb-3">
                            {!! Form::label('gst_number', 'GST Number') !!}
                            {!! Form::text('gst_number', null, ['class' => 'form-control']) !!}
                        </div> --}}

                        <div class="form-group mb-3">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', [
                                'select' => 'Select',
                                '1' => 'Active',
                                '0' => 'Inactive',
                            ], 
                            $customer->status, 
                            ['class' => 'form-select form-select-sm', 'id' => 'status', 'fdprocessedid' => 'hnprge']
                        ) !!}
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('lead_source', 'Lead Source') !!}
                            {!! Form::select('lead_source', [
                                'select' => 'Select',
                                'Old data' => 'Old data',
                                'Telecalling' => 'Telecalling',
                                'WhatsApp Blast' => 'WhatsApp Blast',
                                'Justdial' => 'Justdial',
                                'Referal' => 'Referal',
                                'SMS' => 'SMS',
                                'Webpage' => 'Webpage',
                                'Field visit' => 'Field visit',
                                'Trade Centre' => 'Trade Centre',
                                'Sulekha' => 'Sulekha',
                                'Others' => 'Others',
                            ], 
                            $customer->lead_source, 
                            ['class' => 'form-select form-select-sm', 'id' => 'lead_source', 'fdprocessedid' => 'hnprge']) !!}
                        </div>

                        <div class="form-group mb-3">
                        <label for="business_card" class="custom-label">Business Card</label>
                            {!! Form::file('business_card[]', ['class' => 'form-control', 'multiple' => true]) !!}
                        </div>
                        @if ($customer->business_card)
                        <ul>
                            @foreach (json_decode($customer->business_card, true) as $filePath)
                            <li>{{ basename($filePath) }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p>No business card files uploaded.</p>
                        @endif

                        <div class="form-group mb-3">
                        <label for="gst_document" class="custom-label">Gst Document</label>
                            {!! Form::file('gst_document', ['class' => 'form-control']) !!}
                        </div>
                        <p>{{$customer->gst_document}}</p>

                        <div class="form-group mb-3">
                        <label for="company_name_board" class="custom-label">Company Name Board</label>
                            {!! Form::file('company_name_board', ['class' => 'form-control']) !!}
                        </div>
                        <p>{{$customer->company_name_board}}</p>
                    </div>
                </div>

                <div class="form-group mb-3">
                            <label for="remarks">Remarks:</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3">{{  $customer->remarks }}</textarea>
                        </div>

                <div class="row mb-3">
                    <div class="col-md-12 d-grid gap-3">
                        {!! Form::submit('Update Customer', ['class' => 'btn dash1']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>