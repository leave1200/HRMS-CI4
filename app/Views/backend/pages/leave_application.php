<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Leave Page</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Leave Application</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Form</h4>
            </div>
            <form id="leaveApplicationForm" action="<?= route_to('admin.submit_leave') ?>" method="POST">
                <?= csrf_field() ?>
                <?php if (isset($userStatus) && $userStatus !== 'ADMIN'): ?>
                <!-- Form fields -->
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Name</label>
                    <div class="col-sm-12 col-md-10">
                        <?php if ($userStatus == 'EMPLOYEE'): ?>
                            <!-- Display the name in the input field, but the id is passed in a hidden input -->
                        <input type="text" class="form-control" value="<?= esc($loggedInUser['name']) ?>" readonly>
                        <input type="hidden" name="la_name" value="<?= esc($loggedInUser['id']) ?>">
                        <?php else: ?>
                            <input type="text" class="form-control" value="Select User" readonly>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE' && $userStatus !== 'STAFF'): ?>
                    <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Name</label>
                    <div class="col-sm-12 col-md-10">
                        <select name="la_name" class="form-control" required>
                            <option value="" disabled selected>Select User</option>
                            <?php if (!empty($users) && is_array($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= esc($user['id']) ?>">
                                        <?= esc($user['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No users available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Leave Type</label>
                    <div class="col-sm-12 col-md-10">
                        <select name="la_type" id="la_type" class="form-control" onchange="calculateEndDate()" required>
                            <option value="" disabled selected>Select Leave Type</option>
                            <?php if (!empty($leaveTypes) && is_array($leaveTypes)): ?>
                                <?php foreach ($leaveTypes as $leave): ?>
                                    <option value="<?= esc($leave['l_id']) ?>" data-ldays="<?= esc($leave['l_days']) ?>">
                                        <?= esc($leave['l_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No leave types available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Start Date</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" type="date" id="la_start" name="la_start" onchange="calculateEndDate()" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">End Date</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" type="date" id="la_end" name="la_end" readonly required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12 col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTable to display leave applications -->
<div class="pd-20 card-box mb-30">
    <div class="table-responsive">
        <table id="leaveApplicationsTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaveApplications as $application): ?>
                    <tr>
                        <td><?= esc($application['la_id']) ?></td>
                        <td><?= esc($application['user_name']) ?></td>
                        <td><?= esc($application['leave_type_name']) ?></td>
                        <td><?= esc($application['la_start']) ?></td>
                        <td><?= esc($application['la_end']) ?></td>
                        <td><?= esc($application['status']) ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?= esc($application['la_id']) ?>">Cancel</button>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#leaveApplicationsTable')) {
        // If it is, destroy it first
        $('#leaveApplicationsTable').DataTable().destroy();
    }

    $('#leaveApplicationForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: '<?= route_to('admin.submit_leave') ?>',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload the page after confirmation
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

// Calculate end date based on leave type and start date
function calculateEndDate() {
    var leaveTypeSelect = document.getElementById("la_type");
    var selectedLeaveType = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
    var leaveDays = parseInt(selectedLeaveType.getAttribute('data-ldays')) || 0;
    var startDateInput = document.getElementById("la_start");
    var endDateInput = document.getElementById("la_end");
    var startDateValue = startDateInput.value;

    if (startDateValue) {
        var startDate = new Date(startDateValue);
        startDate.setDate(startDate.getDate() + leaveDays);
        endDateInput.value = startDate.toISOString().split('T')[0];
    } else {
        endDateInput.value = "";
    }
}
</script>
<script>
$(document).ready(function() {
    $('#leaveApplicationsTable').DataTable({
        responsive: true,
    });

    // Approve button click handler
    $('.approve-btn').on('click', function() {
        var applicationId = $(this).data('id');
        
        Swal.fire({
            title: 'Confirm Approval',
            text: 'Are you sure you want to approve this leave application?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '<?= route_to('admin.approve.leave') ?>', // Your route to handle the approval
                    data: { la_id: applicationId, status: 'Approved' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Approved!', response.message, 'success').then(() => {
                                location.reload(); // Reload the page after confirmation
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
                    }
                });
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    // Delete button click handler
    $('.delete-btn').on('click', function() {
        var applicationId = $(this).data('id');

        Swal.fire({
            title: 'Confirm Cancelation',
            text: 'Are you sure you want to Cancel this leave application?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Canceled it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '<?= route_to('admin.cancel.leave') ?>', // Your route to handle deletion
                    data: { la_id: applicationId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Canceled!', response.message, 'success').then(() => {
                                location.reload(); // Reload the page after deletion
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
                    }
                });
            }
        });
    });
});


</script>

<?= $this->endSection() ?>
