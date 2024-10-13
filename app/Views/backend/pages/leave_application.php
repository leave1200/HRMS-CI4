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
                
                <!-- Form fields -->
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Name</label>
                    <div class="col-sm-12 col-md-10">
                        <select name="la_name" class="form-control" required>
                            <option value="" disabled selected>Select Employee</option>
                            <?php if (!empty($employees) && is_array($employees)): ?>
                                <?php foreach ($employees as $employee): ?>
                                    <option value="<?= esc($employee['id']) ?>">
                                        <?= esc($employee['firstname'] . ' ' . $employee['lastname']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No employees available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>


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
<div class="page-header">
    <div class="row">
        <div class="col-md-12">
            <h4>Submitted Leave Applications</h4>
            <table id="leaveApplicationsTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaveApplications as $application): ?>
                        <tr>
                            <td><?= esc($application['la_id']) ?></td>
                            <td><?= esc($application['employee_name']) ?></td>
                            <td><?= esc($application['leave_type_name']) ?></td>
                            <td><?= esc($application['la_start']) ?></td>
                            <td><?= esc($application['la_end']) ?></td>
                            <td><?= esc($application['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#leaveApplicationsTable').DataTable({
        responsive: true,
    });

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

<?= $this->endSection() ?>
