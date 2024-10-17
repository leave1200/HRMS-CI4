// app/Views/backend/pages/home.php

<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home'); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Employee Approval List
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Employee Pending List</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Profile</th>
                    <th scope="col">Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($employee)): ?>
                    <?php foreach ($employee as $index => $emp): ?>
                        <?php if ($emp['result'] !== 'Hired'): // Skip employees with status 
                            ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                            <img src="<?= base_url('backend/images/users/' . htmlspecialchars($emp['picture'] ?? 'userav-min.png')) ?>" alt="Profile Picture" class="avatar-photo ci-avatar-photo" style="width: 50px; height: 50px; border-radius: 50%;">
                            </td>

                            <td><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></td>
                            <td><?= htmlspecialchars($emp['address']) ?></td>
                            <td><?= htmlspecialchars($emp['dob']) ?></td>
                            <td><?= htmlspecialchars($emp['email']) ?></td>
                            <td>
                            <?php if ($emp['result'] === 'Pending'): ?>
                                <button type="button" class="btn btn-sm btn-success" onclick="updateEmployeeStatus(<?= $emp['id'] ?>)">Hire</button>
                            <?php endif; ?>

									  <button type="button" class="btn btn-sm btn-danger" onclick="deleteEmployee(<?= $emp['id'] ?>)">Reject</button>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No employees found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
  
<script>
function deleteEmployee(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this employee!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '<?= route_to('delete_employee') ?>',
                data: {
                    id: id,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>' // Add CSRF token for CodeIgniter 4
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success response:', response); // Log the success response

                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            location.reload(); // Reload page or update table
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message, // Display the error message from response
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText); // Log the error response text
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while processing your request. ' + xhr.responseText,
                        icon: 'error'
                    });
                }
            });
        }
    });
}
</script>
<script>
function updateEmployeeStatus(employeeId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to hire this employee?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, hire!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= route_to('admin.hire_employee') ?>/${employeeId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire('Hired!', 'Employee hired successfully.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error!', 'Failed to update status.', 'error');
                }
            })
            .catch(error => console.error('Error updating employee status:', error));
        }
    });
}

</script>





<?= $this->endSection() ?>
