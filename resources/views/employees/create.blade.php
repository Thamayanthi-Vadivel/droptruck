<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#F98917">
                <h5 class="modal-title text-white fw-bolder text-center" id="exampleModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('employees.store') }}" method="POST"  onsubmit="return validateForm();">
                    @csrf
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                        @if ($errors->has('name'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        @if ($errors->has('email'))
                            <div class="text-danger">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="contact">Contact</label>
                        <input type="text" name="contact" value="{{ old('contact') }}" class="form-control">
                        @if ($errors->has('contact'))
                            <div class="text-danger">{{ $errors->first('contact') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation') }}" class="form-control">
                        @if ($errors->has('designation'))
                            <div class="text-danger">{{ $errors->first('designation') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control">
                        @if ($errors->has('password'))
                            <div class="text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                            <!-- Status Dropdown -->
        <div class="mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-select">
            <option value="select">select</option>
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            @if ($errors->has('status'))
                <div class="text-danger">{{ $errors->first('status') }}</div>
            @endif
        </div>
         <div class="form-group mt-3 d-flex flex-column gap-2">
                        <label for="remarks">Login Permission:</label>
                        <label>
                            <input type="checkbox" name="login_type[]" value="1">
                            Web Login
                        </label>
                        <label>
                            <input type="checkbox" name="login_type[]" value="2">
                            Mobile App Login
                        </label>
                    </div>

                    <div class="form-group mt-3">
                        <label for="remarks">Remarks:</label>
                        <textarea name="remarks" class="form-control" id="remarks" rows="3"></textarea>
                    </div>

                    <!-- Role Dropdown -->
                    <label for="role_id">Role</label>
                    <select name="role_id" class="form-select">
                    <option value="select">select</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->type }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('role_id'))
                        <div class="text-danger">{{ $errors->first('role_id') }}</div>
                    @endif

                    <div class="d-grid gap-3 mt-3">
                        <button type="submit" class="btn dash1">Create Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="login_type"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                checkboxes.forEach(box => {
                    if (box !== this) box.checked = false;
                });
            });
        });
    });
</script>