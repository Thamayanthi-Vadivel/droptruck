<!--<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#updateEmployeeModal">Update</button>-->

<a href="{{ route('suppliers.edit', $supplier->id) }}" data-bs-toggle="modal" data-bs-target="#updateEmployeeModal"><i class="fa fa-edit" style="font-size:17px;"></i></a>

 <div class="modal fade" id="updateEmployeeModal" tabindex="-1" aria-labelledby="updateEmployeeModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-scrollable" role="document">
         <div class="modal-content">
             <div class="modal-header" style="background-color:#F98917">
                 <h5 class="modal-title text-white fw-bolder text-center" id="updateEmployeeModalLabel">Update Employee</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <h1>Edit Supplier</h1>
                     {!! Form::model($supplier, ['route' => ['suppliers.update', $supplier->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
                     <div class="form-group mb-3">
                         {!! Form::label('supplier_name', 'Supplier Name') !!}
                         {!! Form::text('supplier_name', null, ['class' => 'form-control', 'placeholder' => 'Supplier Name']) !!}
                     </div>
                     <div class="form-group mb-3">
                         {!! Form::hidden('indent_id', $supplier->indent_id) !!}
                     </div>
                     <div class="form-group mb-3">
                         {!! Form::label('supplier_type', 'Supplier Type') !!}
                         {!! Form::text('supplier_type', null, ['class' => 'form-control', 'placeholder' => 'Supplier Type']) !!}
                     </div>

                     <div class="form-group mb-3">
                         {!! Form::label('company_name', 'Company Name') !!}
                         {!! Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => 'Company Name']) !!}
                     </div>

                     <div class="form-group mb-3">
                         {!! Form::label('contact_number', 'Contact Number') !!}
                         {!! Form::text('contact_number', null, ['class' => 'form-control', 'placeholder' => 'Contact Number']) !!}
                     </div>

                     <div class="form-group mb-3">
                         {!! Form::label('pan_card_number', 'PAN Card Number') !!}
                         {!! Form::text('pan_card_number', null, ['class' => 'form-control', 'placeholder' => 'PAN Card Number']) !!}
                     </div>
                     <div class="form-group mb-3">
                         {!! Form::label('bank_name', 'Bank Name') !!}
                         {!! Form::text('bank_name', null, ['class' => 'form-control', 'placeholder' => 'Bank Name']) !!}
                     </div>

                     <div class="form-group mb-3">
                         {!! Form::label('ifsc_code', 'IFSC Code') !!}
                         {!! Form::text('ifsc_code', null, ['class' => 'form-control', 'placeholder' => 'IFSC Code']) !!}
                     </div>

                     <div class="form-group mb-3">
                         {!! Form::label('account_number', 'Account Number') !!}
                         {!! Form::text('account_number', null, ['class' => 'form-control', 'placeholder' => 'Account Number']) !!}
                     </div>

                     <div class="form-group mb-3">
                         {!! Form::label('re_account_number', 'Re-Account Number') !!}
                         {!! Form::text('re_account_number', null, ['class' => 'form-control', 'placeholder' => 'Re-Account Number']) !!}
                     </div>


                     <div class="form-group mb-3">
                         {!! Form::label('business_card', 'Upload Business Card Images') !!}
                         {!! Form::file('business_card[]', ['class' => 'form-control', 'multiple' => true]) !!}
                     </div>
                     @if ($supplier->business_card)
                     <ul>
                         @foreach (json_decode($supplier->business_card, true) as $filePath)
                         <li>{{ basename($filePath) }}</li>
                         @endforeach
                     </ul>
                     @else
                     <p>No business card files uploaded.</p>
                     @endif

                     <div class="form-group mb-3">
                         {!! Form::label('pan_card', 'Upload PAN Card') !!}
                         {!! Form::file('pan_card', ['class' => 'form-control']) !!}
                         <p>{{ $supplier->pan_card }}</p>
                     </div>

                     <div class="form-group mb-3">
                         {!! Form::label('memo', 'Upload Others') !!}
                         {!! Form::file('memo', ['class' => 'form-control']) !!}
                         <p>{{ $supplier->memo }}</p>
                     </div>

                     <div class="form-group mb-3">
                         <label for="remarks">Remarks:</label>
                         <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ $supplier->remarks }}</textarea>
                     </div>


                     <div class="form-group d-grid gap-3">
                         {!! Form::submit('Update Supplier', ['class' => 'btn dash1']) !!}
                     </div>
                     {!! Form::close() !!}
                 </div>

             </div>
         </div>
     </div>
 </div>