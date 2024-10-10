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
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home')?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Designation</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">Designation</h4>
        </div>
    </div>
        <form id="designationForm" action="<?= route_to('designation_save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" class="form-control" name="designation" style="width: 100%; height: 38px" required>
                            <button type="submit" class="btn btn-outline-primary mt-2">Add</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Assigned</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Designation Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($designations)): ?>
                    <?php foreach ($designations as $index => $designation): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($designation['name']) ?></td>
                            <td>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary edit-btn" data-id="<?= $designation['id'] ?>" data-name="<?= htmlspecialchars($designation['name']) ?>">Edit</a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteDesignation(<?= $designation['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No designations found</td>
                    </tr> 
                    
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Edit Designation -->
<div class="modal fade" id="editDesignationModal" tabindex="-1" aria-labelledby="editDesignationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDesignationModalLabel">Edit Designation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDesignationForm">
                <div class="modal-body">
                    <input type="hidden" id="editDesignationId" name="id">
                    <div class="form-group">
                        <label for="editDesignationName">Designation Name</label>
                        <input type="text" class="form-control" id="editDesignationName" name="designation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/backend/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#designationForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            
            var form = $(this);
            var formData = form.serialize();

            // Simulate form submission with AJAX
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Show SweetAlert for success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                                // Optionally reload the page or perform other actions
                                // Example: location.reload(); // Reload the page
                            }
                        });
                    } else {
                        // Show SweetAlert for error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // Show an alert for any unexpected errors (optional)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your request.'
                    });
                }
            });
        });
    });
</script>
<script>
function deleteDesignation(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this designation!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '<?= route_to('delete_designation') ?>',
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
    $(document).ready(function() {
        // Handle edit button clicks
        $('.edit-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#editDesignationId').val(id);
            $('#editDesignationName').val(name);

            $('#editDesignationModal').modal('show');
        });

        // Handle form submission for editing designations
        $('#editDesignationForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: '<?= route_to('update_designation') ?>', // Replace with your update route
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editDesignationModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your request.'
                    });
                }
            });
        });
    });
</script>



<?= $this->endSection() ?>
