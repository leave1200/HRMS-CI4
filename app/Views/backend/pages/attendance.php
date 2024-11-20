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
<div class="tabs">
    <div class="tab-content">
        <div id="attendanceTab1" class="tab-pane fade show active">
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
                                <label>User</label>
                                <input type="text" id="userInput" class="form-control" placeholder="Type user's name..." autocomplete="off" required>
                                <ul id="userList" class="list-group" style="display: none; position: absolute; max-height: 150px; overflow-y: auto; z-index: 1000;"></ul>
                                <input type="hidden" name="user" id="selectedUserId" required>
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
                            <button type="button" id="signInButton" class="btn btn-outline-primary mt-2" onclick="signInUser()">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Attendance Records</h4>
    </div>
    <div class="pb-20">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <script>
                    function filterTable() {
                        const input = document.getElementById('searchInput');
                        const filter = input.value.toLowerCase();
                        const rows = document.querySelectorAll('#DataTables_Table_0_wrapper tbody tr');

                        rows.forEach(row => {
                            const nameCell = row.cells[1]; // Assuming the Name is the second column
                            if (nameCell) {
                                const txtValue = nameCell.textContent || nameCell.innerText;
                                row.style.display = txtValue.toLowerCase().includes(filter) ? "" : "none";
                            }
                        });
                    }
                </script>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="data-table table table-striped table-hover dataTable no-footer dtr-inline collapsed" id="DataTables_Table_0" role="grid">
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
                                                    <button type="button" class="btn btn-danger btn-sm ml-2" onclick="signOutAttendance(<?= esc($attendance['id']) ?>, 'am')">Sign Out (AM)</button>
                                                <?php else: ?>
                                                    <span>No AM Sign In</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-danger">AM Signed Out: <?= esc(date('H:i', strtotime($attendance['sign_out']))) ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- PM Sign In and Out status -->
                                        <td>
                                            <?php if (empty($attendance['pm_sign_in'])): ?>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="signInPmUser(<?= esc($attendance['id']) ?>)">Sign In (PM)</button>
                                            <?php else: ?>
                                                <span class="badge bg-success">PM Signed In: <?= esc(date('H:i', strtotime($attendance['pm_sign_in']))) ?></span>
                                                <?php if (empty($attendance['pm_sign_out'])): ?>
                                                    <button type="button" class="btn btn-danger btn-sm ml-2" onclick="signOutAttendance(<?= esc($attendance['id']) ?>, 'pm')">Sign Out (PM)</button>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">PM Signed Out: <?= esc(date('H:i', strtotime($attendance['pm_sign_out']))) ?></span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No attendance records found.</td>
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
const employees = <?= json_encode($employees); ?>; // Fetching employee data from PHP

const input = document.getElementById('employeeInput');
const list = document.getElementById('employeeList');
const selectedEmployeeId = document.getElementById('selectedEmployeeId');

input.addEventListener('input', function() {
    const filterValue = this.value.toLowerCase();
    list.innerHTML = ''; // Clear previous results
    list.style.display = 'none'; // Hide the list initially

    if (filterValue) {
        const filteredEmployees = employees.filter(employee =>
            `${employee.firstname} ${employee.lastname}`.toLowerCase().includes(filterValue)
        );

        filteredEmployees.forEach(employee => {
            const li = document.createElement('li');
            li.textContent = `${employee.firstname} ${employee.lastname}`;
            li.className = 'list-group-item'; // Bootstrap list group class
            li.onclick = () => {
                input.value = `${employee.firstname} ${employee.lastname}`; // Set input value
                selectedEmployeeId.value = employee.id; // Set hidden input value
                
                // Update PM sign-in fields
                $('#employeeNumberInput2').val(employee.id);
                $('#employeeNameInput2').val(`${employee.firstname} ${employee.lastname}`);
                $('#selectedEmployeeId2').val(employee.id);

                list.style.display = 'none'; // Hide the list after selection
            };
            list.appendChild(li);
        });

        if (filteredEmployees.length > 0) {
            list.style.display = 'block'; // Show the list if there are results
        }
    }
});


// Hide the list if clicking outside
document.addEventListener('click', (event) => {
    if (!input.contains(event.target) && !list.contains(event.target)) {
        list.style.display = 'none';
    }
});

function signInEmployee() {
    const selectedEmployee = selectedEmployeeId.value;
    if (!selectedEmployee) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select an employee.',
        });
        return;
    }

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
                url: $('#signInForm').attr('action'),
                method: $('#signInForm').attr('method'),
                data: $('#signInForm').serialize(),
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
                error: function() {
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
    const sessionText = session === 'am' ? 'AM' : 'PM';

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
                url: '<?= route_to('admin.attendance_signout') ?>',
                method: 'post',
                data: {
                    id: attendanceId,
                    session: session,
                    csrf_token_name: '<?= csrf_hash() ?>' // Use correct CSRF field name
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Signed Out',
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
<script>
$(document).ready(function() {
    const table = $('#DataTables_Table_0').DataTable();

    $('#DataTables_Table_0 tbody').on('click', 'tr', function() {
        const employeeId = $(this).find('td:eq(0)').text(); // Get employee ID from the first column
        const employeeName = $(this).find('td:eq(1)').text(); // Get employee name from the second column
        const officeId = $(this).find('td:eq(2)').text(); // Get office ID from the third column
        const positionId = $(this).find('td:eq(3)').text(); // Get position ID from the fourth column

        // Populate the fields in the PM Sign In form
        $('#employeeNumberInput2').val(employeeId);
        $('#employeeNameInput2').val(employeeName);
        $('#selectedEmployeeId2').val(employeeId);

        // Set Office and Position based on selected employee
        $('#officeSelect2').val(officeId).change(); // Set the office select value
        $('#positionSelect2').val(positionId).change(); // Set the position select value
    });
});

</script>
<script>
function signInPmEmployee(attendanceId) {
    if (!attendanceId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select a valid attendance record.',
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to sign in for PM?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, sign in for PM!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= route_to('attendance.pm_save') ?>',
                method: 'POST',
                data: { attendance_id: attendanceId }, // Use the passed attendance ID directly
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'PM Signed In',
                            text: response.message,
                        }).then(() => {
                            location.reload(); // Reload the page on success
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
                    console.error('AJAX Error:', xhr.responseText); // Log the full response for debugging
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error processing your request. Please try again.',
                    });
                }
            });
        }
    });
}

</script>
<?= $this->endSection() ?>
