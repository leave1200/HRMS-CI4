<?= $this->extend('backend/layout/pages-layout') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-knob@1.2.13/dist/jquery.knob.min.css">
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Dashboard</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">Attendance</h4>
        </div>
    </div>
    <form id="signInForm" action="<?= route_to('attendance_save') ?>" method="post">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Employee</label>
                    <select name="employee" id="employeeSelect" class="form-control" style="width: 50%; height: 38px" required>
                        <option value="" disabled selected>Select an employee</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['id'] ?>">
                                <?= $employee['firstname'] ?> <?= $employee['lastname'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Office</label>
                    <select name="office" class="form-control" style="width: 50%; height: 38px" required>
                        <?php foreach ($designations as $designation): ?>
                            <option value="<?= $designation['id'] ?>"><?= $designation['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <select name="position" class="form-control" style="width: 50%; height: 38px" required>
                        <?php foreach ($positions as $position): ?>
                            <option value="<?= $position['position_id'] ?>"><?= $position['position_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" class="btn btn-outline-primary mt-2" onclick="signInEmployee()">Sign In/Out</button>
            </div>
        </div>
    </form>
</div>
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Attendance Records</h4>
    </div>
    <div class="pb-20">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length" id="DataTables_Table_0_length">
                        <label>Show
                            <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="-1">All</option>
                            </select> entries
                        </label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div id="DataTables_Table_0_filter" class="dataTables_filter">
                        <label>Search:
                            <input type="search" class="form-control form-control-sm" placeholder="Search" aria-controls="DataTables_Table_0">
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                <table class="data-table table stripe hover nowrap dataTable no-footer dtr-inline collapsed" id="DataTables_Table_0" role="grid">
                    <thead>
                        <tr role="row">
                            <th>#</th>
                            <th>Name</th>
                            <th>Office</th>
                            <th>Position</th>
                            <th>AM Sign In</th>
                            <th>Sign Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($attendances)): ?>
                            <?php foreach ($attendances as $attendance): ?>
                                <tr>
                                    <td><?= esc($attendance['id']) ?></td>
                                    <td><?= esc($attendance['name']) ?></td>
                                    <td><?= esc($attendance['office']) ?></td>
                                    <td><?= esc($attendance['position']) ?></td>
                                    
                                        <!-- AM Sign Out status -->
                                        <td>
                                            <?php if (empty($attendance['sign_out'])): ?>
                                                <?php if (!empty($attendance['sign_in'])): ?>
                                                    <span class="badge bg-success">AM Signed In: <?= esc(date('H:i', strtotime($attendance['sign_in']))) ?></span>
                                                    <button type="button" class="btn btn-danger ml-2" onclick="signOutAttendance(<?= esc($attendance['id']) ?>, 'am')">Sign Out (AM)</button>
                                                <?php else: ?>
                                                    <span>No AM Sign In</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-danger">AM Signed Out: <?= esc(date('H:i', strtotime($attendance['sign_out']))) ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- PM Sign Out status -->
                                        <td>
                                            <?php if (empty($attendance['pm_sign_out'])): ?>
                                                <?php if (!empty($attendance['pm_sign_in'])): ?>
                                                    <span class="badge bg-success">PM Signed In: <?= esc(date('H:i', strtotime($attendance['pm_sign_in']))) ?></span>
                                                    <button type="button" class="btn btn-danger ml-2" onclick="signOutAttendance(<?= esc($attendance['id']) ?>, 'pm')">Sign Out (PM)</button>
                                                <?php else: ?>
                                                    <span>No PM Sign In</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-danger">PM Signed Out: <?= esc(date('H:i', strtotime($attendance['pm_sign_out']))) ?></span>
                                            <?php endif; ?>
                                            </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No attendance records found.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                </table>
                </div>
            </div>
        </div>                        
    </div>
</div>

<script src="/backend/src/plugins/sweetalert2/sweetalert2.all.js"></script>

<script>
function signInEmployee() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to sign in this employee?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, sign in!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: $('#signInForm').attr('action'), // Use the form's action URL
                method: $('#signInForm').attr('method'), // Use the form's method
                data: $('#signInForm').serialize(), // Serialize form data
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Signed In',
                            text: response.message,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error processing your request.',
                    });
                }
            });
        }
    });
}

function signOutAttendance(attendanceId, session) {
    const sessionText = session === 'am' ? 'AM' : 'PM'; // Determine the session type

    Swal.fire({
        title: 'Are you sure?',
        text: `You want to sign out this employee for ${sessionText}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Yes, sign out (${sessionText})!`
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= route_to('attendance_signout') ?>',
                method: 'post',
                data: {
                    id: attendanceId,
                    session: session, // Send the session type
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>' // Include CSRF token
                },
                dataType: 'json', // Expecting a JSON response
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Signed Out',
                            text: response.message,
                        }).then(() => {
                            location.reload(); // Reload page to refresh the table
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log the error for debugging
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error processing your request.',
                    });
                }
            });
        }
    });
}


function signOutPM() {
    const employeeId = $('#employeeSelect').val(); // Get selected employee

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to sign out this employee?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, sign out!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= route_to('attendance_signout') ?>', // Update with your route for sign out
                method: 'post',
                data: {
                    employee: employeeId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>' // Include CSRF token
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Signed Out',
                            text: response.message,
                        }).then(() => {
                            location.reload(); // Reload page to refresh the table
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error processing your request.',
                    });
                }
            });
        }
    });
}

</script>

<?= $this->endSection() ?>
