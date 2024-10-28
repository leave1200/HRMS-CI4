<?= $this->extend('backend/layout/pages-layout') ?>
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
                    <li class="breadcrumb-item active" aria-current="page">
                        Attendance Reports
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Date Filter Form -->
<form method="get" action="<?= route_to('attendance_report') ?>" class="mb-4">
    <div class="form-group">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?= esc($startDate) ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?= esc($endDate) ?>" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Sign In/Sign Out Record</h4>
        </div>
        <div class="mb-10 pull-right">
            <input type="text" id="searchInput" placeholder="Search by Name" onkeyup="filterTable()" class="form-control">
        </div>

    </div>
    <button onclick="printDataTable()" class="btn btn-primary">Print</button>
    <div class="table-responsive">
        <div id="print-area">
            <table class="data-table table stripe hover nowrap dataTable no-footer dtr-inline collapsed" id="DataTables_Table_0" role="grid">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Office</th>
                        <th scope="col">Position</th>
                        <th scope="col">AM Sign In</th>
                        <th scope="col">AM Sign Out</th>
                        <th scope="col">PM Sign In</th>
                        <th scope="col">PM Sign Out</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attendances)): ?>
                        <?php foreach ($attendances as $index => $attendance): ?>
                            <tr>
                                <td><?= ($index + 1) + (($pager->getCurrentPage() - 1) * $perPage) ?></td>
                                <td><small><?= htmlspecialchars(explode(' ', $attendance['sign_in'])[0]) ?></small></td>
                                <td><?= htmlspecialchars($attendance['name']) ?></td>
                                <td><?= htmlspecialchars($attendance['office']) ?></td>
                                <td><?= htmlspecialchars($attendance['position']) ?></td>
                                <td>
                                        <?php 
                                            // Display AM Sign In (time only)
                                            $amSignInTime = isset($attendance['sign_in']) ? date('H:i:s', strtotime($attendance['sign_in'])) : 'N/A';
                                            echo $amSignInTime;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            // Display AM Sign Out (time only)
                                            $amSignOutTime = isset($attendance['sign_out']) ? date('H:i:s', strtotime($attendance['sign_out'])) : 'N/A';
                                            echo $amSignOutTime;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            // Display PM Sign In (time only)
                                            $pmSignInTime = isset($attendance['pm_sign_in']) ? date('H:i:s', strtotime($attendance['pm_sign_in'])) : 'N/A';
                                            echo $pmSignInTime;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            // Display PM Sign Out (time only)
                                            $pmSignOutTime = isset($attendance['pm_sign_out']) ? date('H:i:s', strtotime($attendance['pm_sign_out'])) : 'N/A';
                                            echo $pmSignOutTime;
                                        ?>
                                    </td>
                                        <?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?>
                                    <td>
                                        <button type="button" class="btn btn-secondary" onclick="deleteAttendance(<?= $attendance['id'] ?>)">Delete</button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">No attendance records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="clearfix">
        <div class="pull-right">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php if ($hasPrevious): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&start_date=<?= esc($startDate) ?>&end_date=<?= esc($endDate) ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link">&laquo; Previous</span>
                        </li>
                    <?php endif; ?>

                    <li class="page-item disabled">
                        <span class="page-link"><?= $currentPage ?></span>
                    </li>

                    <?php if ($hasNext): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&start_date=<?= esc($startDate) ?>&end_date=<?= esc($endDate) ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link">Next &raquo;</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function printDataTable() {
    var name = filteredRows.length > 0 ? filteredRows[0].cells[2].textContent.trim() : "No Name Found";

    var tableBody = document.querySelector("#DataTables_Table_0 tbody");
    var filteredTableContent = Array.from(tableBody.querySelectorAll("tr")).map(row => {
        if (row.style.display !== 'none') {
            let newRow = row.cloneNode(true);
            newRow.removeChild(newRow.children[9]); // Remove Action (last column)
            newRow.removeChild(newRow.children[0]); // Remove # (first column)
            newRow.removeChild(newRow.children[1]); // Remove Office (adjusted index after removing #)
            newRow.removeChild(newRow.children[2]); // Remove Position (adjusted index after removing Office)
            newRow.removeChild(newRow.children[1]);
            return newRow.outerHTML; // Convert back to HTML string
        }
        return '';
    }).join('');

    var numberOfDays = tableBody.querySelectorAll("tr").length;
    var numberOfSaturdays = Array.from(tableBody.querySelectorAll("tr")).filter(row => {
        let dateCell = row.querySelector("td:nth-child(2)");
        let dateText = dateCell ? dateCell.textContent : '';
        return new Date(dateText).getDay() === 6; // Saturday is day 6
    }).length;

    // Construct the custom print layout
    var printContent = `
        <div style="font-family: Arial, sans-serif; padding: 20px;">
            <h2>Attendance Report</h2>
            <p><strong>Name:</strong> ${name}</p>
            <p><strong>Number of days:</strong> ${numberOfDays}</p>
            <p><strong>Number of Saturdays:</strong> ${numberOfSaturdays}</p>
            <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>AM Sign In</th>
                        <th>AM Sign Out</th>
                        <th>PM Sign In</th>
                        <th>PM Sign Out</th>
                    </tr>
                </thead>
                <tbody>
                    ${filteredTableContent}
                </tbody>
            </table>
        </div>
    `;

    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}

</script>












<script>
function deleteAttendance(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= route_to('attendance.delete') ?>',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        response,
                        'success'
                    ).then(() => {
                        location.reload(); 
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseText,
                        'error'
                    );
                }
            });
        }
    });
}
</script>
<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#DataTables_Table_0 tbody tr');

    rows.forEach(row => {
        const nameCell = row.cells[2]; // Assuming the Name is the third column
        if (nameCell) {
            const txtValue = nameCell.textContent || nameCell.innerText;
            row.style.display = txtValue.toLowerCase().includes(filter) ? "" : "none";
        }
    });
}
</script>

<?= $this->endSection() ?>
