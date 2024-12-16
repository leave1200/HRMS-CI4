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
    <button onclick="printDataTable()" class="btn btn-primary"><i class="icon-copy bi bi-printer"></i>Print</button>
    <!-- <button onclick="fetchArchivedData()" class="btn btn-secondary">View Archived Records</button> -->
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
                                        $amSignInTime = isset($attendance['sign_in']) ? date('H:i:s', strtotime($attendance['sign_in'])) : 'N/A';
                                        echo $amSignInTime;
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $amSignOutTime = isset($attendance['sign_out']) ? date('H:i:s', strtotime($attendance['sign_out'])) : 'N/A';
                                        echo $amSignOutTime;
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $pmSignInTime = isset($attendance['pm_sign_in']) ? date('H:i:s', strtotime($attendance['pm_sign_in'])) : 'N/A';
                                        echo $pmSignInTime;
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $pmSignOutTime = isset($attendance['pm_sign_out']) ? date('H:i:s', strtotime($attendance['pm_sign_out'])) : 'N/A';
                                        echo $pmSignOutTime;
                                    ?>
                                </td>
                                <?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?>
                                    <td>
                                        <button type="button" class="btn btn-secondary" onclick="deleteAttendance(<?= $attendance['id'] ?>)">Delete</button>
                                        <!-- <button type="button" class="btn btn-primary" onclick="archiveAttendance(<?= $attendance['id'] ?>)">Archive</button> -->
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
<!-- Archived Records Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Archived Attendance Records</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="archivedTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Office</th>
                                <th>Position</th>
                                <th>AM Sign In</th>
                                <th>AM Sign Out</th>
                                <th>PM Sign In</th>
                                <th>PM Sign Out</th>
                            </tr>
                        </thead>
                        <tbody id="archivedData">
                            <!-- Archived records will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
function printDataTable() {
    // Filter visible rows
    var filteredRows = Array.from(document.querySelectorAll("#DataTables_Table_0 tbody tr")).filter(row => row.style.display !== 'none');

    // Extract the name
    var name = filteredRows.length > 0 ? filteredRows[0].cells[2].textContent.trim() : "No Name Found";

    // Extract the month and year from the first visible row's date
    var firstVisibleRowDate = filteredRows.length > 0 ? filteredRows[0].querySelector("td:nth-child(2)").textContent.trim() : '';
    var monthYearText = '';
    if (firstVisibleRowDate) {
        var firstDate = new Date(firstVisibleRowDate);
        var month = firstDate.toLocaleString('default', { month: 'long' }); // e.g., January
        var year = firstDate.getFullYear(); // e.g., 2024
        monthYearText = `For the month of ${month}, ${year}`;
    } else {
        monthYearText = "For the month of ____________________, 20_______"; // Default text
    }

    // Calculate regular days and Saturdays
    var numberOfDays = filteredRows.length;
    var numberOfSaturdays = filteredRows.filter(row => {
        let dateCell = row.querySelector("td:nth-child(2)");
        let dateText = dateCell ? dateCell.textContent : '';
        return new Date(dateText).getDay() === 6; // Saturday is day 6
    }).length;

    // Function to calculate the time difference in hours and minutes
    function calculateTimeDifference(startTime, endTime) {
        var start = new Date('1970-01-01T' + startTime + 'Z');
        var end = new Date('1970-01-01T' + endTime + 'Z');
        var diffInMillis = end - start;

        var diffInHours = Math.floor(diffInMillis / 1000 / 60 / 60);
        var diffInMinutes = Math.floor((diffInMillis % (1000 * 60 * 60)) / (1000 * 60));

        return { hours: diffInHours, minutes: diffInMinutes };
    }

    // Generate table rows for dates, arrival, and departure times
    var tableRows = Array.from({ length: 31 }).map((_, index) => {
        let date = index + 1; // Dates 1 to 31
        let row = filteredRows.find(row => {
            let dateCell = row.querySelector("td:nth-child(2)");
            return dateCell && parseInt(new Date(dateCell.textContent).getDate()) === date;
        });

        let arrivalAM = row ? row.querySelector("td:nth-child(6)").textContent.trim() : ''; // AM Arrival
        let departureAM = row ? row.querySelector("td:nth-child(7)").textContent.trim() : ''; // AM Departure
        let arrivalPM = row ? row.querySelector("td:nth-child(8)").textContent.trim() : ''; // PM Arrival
        let departurePM = row ? row.querySelector("td:nth-child(9)").textContent.trim() : ''; // PM Departure

        // Calculate regular worked time and undertime for each row
        let workedHoursAM = 0;
        let workedMinutesAM = 0;
        let workedHoursPM = 0;
        let workedMinutesPM = 0;

        // Calculate AM worked time
        if (arrivalAM && departureAM) {
            let amWorkDuration = calculateTimeDifference(arrivalAM, departureAM);
            workedHoursAM = amWorkDuration.hours;
            workedMinutesAM = amWorkDuration.minutes;
        }

        // Calculate PM worked time
        if (arrivalPM && departurePM) {
            let pmWorkDuration = calculateTimeDifference(arrivalPM, departurePM);
            workedHoursPM = pmWorkDuration.hours;
            workedMinutesPM = pmWorkDuration.minutes;
        }

        // Calculate total worked hours and minutes for the day
        let totalWorkedHours = workedHoursAM + workedHoursPM;
        let totalWorkedMinutes = workedMinutesAM + workedMinutesPM;

        // Calculate undertime for the day (assuming a full 8-hour workday)
        let totalUndertime = 8 - (totalWorkedHours + totalWorkedMinutes / 60);
        let undertimeHours = Math.floor(totalUndertime);
        let undertimeMinutes = Math.round((totalUndertime - undertimeHours) * 60);

        // Return table row
        return ` 
            <tr>
                <td>${date}</td>
                <td>${arrivalAM}</td>
                <td>${departureAM}</td>
                <td>${arrivalPM}</td>
                <td>${departurePM}</td>
                <td>${undertimeHours}</td>
                <td>${undertimeMinutes}</td>
            </tr>
        `;
    }).join('');

    // Construct the two forms side by side
    var printContent = `
        <div style="font-family: Arial, sans-serif; padding: 20px;">
            <div style="display: flex; justify-content: space-between;">
                <!-- First Form -->
                <div style="width: 48%; border: 1px solid #000; padding: 10px;">
                    <p style="text-align: left;"><i>Civil Service Form No. 48</i></p>
                    <h3 style="text-align: center;">DAILY TIME RECORD</h3>
                    <p style="text-align: center;"><u>${name}</u><br><strong style="margin-top: -30px;">(Name)</strong></p>
                    <p style="text-align: center;">${monthYearText}</p>
                    <p style="text-align: left;">Official hours of arrival<br> and departure</p>
                    <p style="text-align: center;margin-top: -65px;">Regular Days: ${numberOfDays}</p>
                    <p style="text-align: center;margin-top: -10px;">Saturdays: ${numberOfSaturdays}</p>
                    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center; border-collapse: collapse;">
                        <thead>
                           <tr>
                                <th rowspan="2">Date</th>
                                <th colspan="2">AM</th>
                                <th colspan="2">PM</th>
                                <th colspan="2">Undertime</th>
                            </tr>
                            <tr>
                                <th>Arrival</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Departure</th>
                                <th>Hour</th>
                                <th>Minutes</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                    <p style="margin-top: 20px;"><strong>TOTAL Time Worked:</strong> ${totalWorkedTimeHours} hours ${totalWorkedTimeMinutes} minutes</p>
                    <p style="margin-top: 20px;"><strong>TOTAL Not Work:</strong> ${totalUndertimeHours} hours ${totalUndertimeMinutes} minutes</p>
                    <p style="margin-top: 50px; text-align: center;">Administrator</p>
                    <p style="margin-top: -25px; text-align: center;">Administrator</p>
                    <p style="margin-top: 20px; text-align: justify;">I CERTIFY on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure from office.</p>
                </div>

                <!-- Second Form -->
                <div style="width: 48%; border: 1px solid #000; padding: 10px;">
                    <p style="text-align: left;"><i>Civil Service Form No. 48</i></p>
                    <h3 style="text-align: center;">DAILY TIME RECORD</h3>
                    <p style="text-align: center;"><u>${name}</u><br><strong style="margin-top: -30px;">(Name)</strong></p>
                    <p style="text-align: center;">${monthYearText}</p>
                    <p style="text-align: left;">Official hours of arrival<br> and departure</p>
                    <p style="text-align: center;margin-top: -65px;">Regular Days: ${numberOfDays}</p>
                    <p style="text-align: center;margin-top: -10px;">Saturdays: ${numberOfSaturdays}</p>
                    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th rowspan="2">Date</th>
                                <th colspan="2">AM</th>
                                <th colspan="2">PM</th>
                                <th colspan="2">Undertime</th>
                            </tr>
                             <tr>
                                <th>Arrival</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Departure</th>
                                <th>Hour</th>
                                <th>Minute</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                    <p style="margin-top: 20px;"><strong>TOTAL Time Worked:</strong> ${totalWorkedTimeHours} hours ${totalWorkedTimeMinutes} minutes</p>
                    <p style="margin-top: 20px;"><strong>TOTAL Not Work:</strong> ${totalUndertimeHours} hours ${totalUndertimeMinutes} minutes</p>
                    <p style="margin-top: 50px; text-align: center;">Administrator</p>
                    <p style="margin-top: -25px; text-align: center;">Administrator</p>
                    <p style="margin-top: 20px; text-align: justify;">I CERTIFY on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure from office.</p>
                </div>
            </div>
        </div>
    `;

    // Print the content
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}
</script>





<script>
function fetchArchivedData() {
    $.ajax({
        url: '<?= route_to('attendance.archived') ?>', // Adjust this route according to your setup
        type: 'GET',
        success: function(data) {
            const archivedData = JSON.parse(data); // Assuming the server returns JSON
            let rows = '';

            archivedData.forEach((record, index) => {
                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${record.date}</td>
                        <td>${record.name}</td>
                        <td>${record.office}</td>
                        <td>${record.position}</td>
                        <td>${record.am_sign_in}</td>
                        <td>${record.am_sign_out}</td>
                        <td>${record.pm_sign_in}</td>
                        <td>${record.pm_sign_out}</td>
                    </tr>
                `;
            });

            $('#archivedData').html(rows); // Populate the modal with data
            $('#archiveModal').modal('show'); // Show the modal
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Failed to fetch archived records.', 'error');
        }
    });
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
<script>
function archiveAttendance(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to archive this attendance record?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, archive it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= route_to('attendance.archive') ?>',
                type: 'POST',
                data: { id: id }, // Send the ID as part of the request data
                success: function(response) {
                    Swal.fire(
                        'Archived!',
                        'Attendance record archived successfully.',
                        'success'
                    ).then(() => {
                        location.reload(); 
                    });
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                        ? xhr.responseJSON.message 
                        : 'An unexpected error occurred.';
                    Swal.fire(
                        'Error!',
                        'Failed to archive attendance record: ' + errorMessage,
                        'error'
                    );
                }
            });
        } else {
            Swal.fire(
                'Cancelled',
                'The attendance record is safe :)',
                'info'
            );
        }
    });
}
</script>


<?= $this->endSection() ?>
