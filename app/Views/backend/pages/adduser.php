<?= $this->extend('backend/layout/pages-layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Add User</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="min-height-200px">
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <h4 class="text-blue h4">Add New User</h4>
            <p class="mb-30">Fill in the details below to add a new user.</p>
        </div>
        <form id="addUserForm" action="<?= route_to('user.store') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employee_id">Select Employee:</label>
                        <select class="form-control" id="employee_id" name="employee_id" required>
                            <option value="">Select an employee</option>
                            <?php if (!empty($employees)): ?>
                                <?php foreach ($employees as $employee): ?>
                                    <option value="<?= $employee['id'] ?>"
                                            data-email="<?= $employee['email'] ?>"
                                            data-name="<?= $employee['firstname'] . ' ' . $employee['lastname'] ?>"
                                            <?= old('employee_id') == $employee['id'] ? 'selected' : '' ?>>
                                        <?= $employee['firstname'] . ' ' . $employee['lastname'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No employees found</option>
                            <?php endif; ?>
                        </select>
                        <div class="text-danger"><?= $validation->getError('employee_id') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                        <div class="text-danger"><?= $validation->getError('username') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                        <div class="text-danger"><?= $validation->getError('email') ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="text-danger"><?= $validation->getError('password') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="text-danger"><?= $validation->getError('confirm_password') ?></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea class="form-control" id="bio" name="bio"><?= old('bio') ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="ADMIN" <?= old('status') === 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
                    <option value="EMPLOYEE" <?= old('status') === 'EMPLOYEE' ? 'selected' : '' ?>>EMPLOYEE</option>
                    <option value="STAFF" <?= old('status') === 'STAFF' ? 'selected' : '' ?>>STAFF</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('addUserForm');
        
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission
            
            var formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'User added successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = data.redirect; // Redirect after success
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'An error occurred while adding the user.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error); // Log the full error object to the console
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'An error occurred while adding the user.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });

        var employeeSelect = document.getElementById('employee_id');
        var emailInput = document.getElementById('email');

        employeeSelect.addEventListener('change', function () {
            var selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
            var email = selectedOption.getAttribute('data-email');

            emailInput.value = email;
        });
    });
</script>

<?= $this->endSection() ?>
