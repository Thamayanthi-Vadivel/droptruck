<div class="modal fade" id="updateEmployeeModal" tabindex="-1" aria-labelledby="updateEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#F98917">
                <h5 class="modal-title text-white fw-bolder text-center" id="updateEmployeeModalLabel">Update Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employees.update', $users->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $users->role_id }}">
                    <label for="name">Name</label>
                    <input type="text" name="name" value="{{ $users->name }}" class="form-control">
                        @if ($errors->has('name'))
                        <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif


                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ $users->email }}" class="form-control mt-3" required>
                    @if ($errors->has('email'))
                        <div class="text-danger">{{ $errors->first('email') }}</div>
                        @endif

                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control mt-3">
                    @if ($errors->has('password'))
                        <div class="text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" value="{{ $users->contact }}" class="form-control mt-3" required>
                    @if ($errors->has('contact'))
                        <div class="text-danger">{{ $errors->first('contact') }}</div>
                        @endif

                    <label for="designation">Designation</label>
                    <input type="text" name="designation" value="{{ $users->designation }}" class="form-control mt-3" required>
                    @if ($errors->has('designation'))
                        <div class="text-danger">{{ $errors->first('designation') }}</div>
                        @endif

                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $users->status === 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $users->status === 0 ? 'selected' : '' }}>Inactive</option>
                        </select>

                        @if ($errors->has('status'))
                        <div class="text-danger">{{ $errors->first('status') }}</div>
                        @endif
                    </div>

                    <div class="form-group mt-3 d-flex flex-column gap-2 mb-3">
                        <label for="login_type">Login Permission:</label>
                        <label>
                            <input type="checkbox" name="login_type[]" value="1" {{ $users->login_type == 1 ? 'checked' : '' }}>
                            Web Login
                        </label>
                        <label>
                            <input type="checkbox" name="login_type[]" value="2" {{ $users->login_type == 2 ? 'checked' : '' }}>
                            Mobile App Login
                        </label>
                    </div>

                    <label for="remarks">Remarks:</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ $users->remarks }}</textarea>

                    <div class="d-grid gap-3 mt-3">
                        <button type="submit" class="btn dash1">Update Employee</button>
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
