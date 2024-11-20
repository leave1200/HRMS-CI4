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
                        <th>Action</th> <!-- New Action Column -->
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
                                <button class="btn btn-success btn-sm approve-btn" data-id="<?= esc($application['la_id']) ?>">Approve</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#leaveApplicationsTable')) {
        // If it is, destroy it first
        $('#leaveApplicationsTable').DataTable().destroy();
    }
});
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

<?= $this->endSection() ?>
