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
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Leave Type</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="pd-20 card-box mb-30">
    <h2 class="mb-4">Add New Leave Type</h2>
    <form id="addRecordForm" action="<?= route_to('save_leave') ?>" method="post">
        <?= csrf_field() ?>
        <!-- Input for l_name -->
        <div class="mb-3">
            <label for="l_name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="l_name" name="l_name" maxlength="255" required>
        </div>

        <!-- Input for l_description -->
        <div class="mb-3">
            <label for="l_description" class="form-label">Description:</label>
            <input type="text" class="form-control" id="l_description" name="l_description" maxlength="255" required>
        </div>

        <!-- Input for l_days -->
		<div class="mb-3">
			<label for="l_days" class="form-label">Number Of Days:</label>
			<input type="text" class="form-control" id="l_days" name="l_days" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="3" required>
		</div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Leave Types Records</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Leave Type Name</th>
                    <th scope="col">Description</th>
					<th scope="col">Number of days</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($leaveTypes)): ?>
                    <?php foreach ($leaveTypes as $index => $leaveType): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($leaveType['l_name']) ?></td>
                            <td><?= htmlspecialchars($leaveType['l_description']) ?></td>
							<td><?= htmlspecialchars($leaveType['l_days']) ?></td>
                            <td>
							<a href="javascript:void(0);" 
								class="btn btn-sm btn-primary edit-btn" 
								data-id="<?= $leaveType['l_id'] ?>" 
								data-name="<?= htmlspecialchars($leaveType['l_name']) ?>" 
								data-description="<?= htmlspecialchars($leaveType['l_description']) ?>" 
								data-days="<?= $leaveType['l_days'] ?>"
								data-toggle="modal" 
								data-target="#addLeaveTypeModal">
								Edit
								</a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteLeave(<?= $leaveType['l_id'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No leave types found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="addLeaveTypeModal" tabindex="-1" role="dialog" aria-labelledby="addLeaveTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLeaveTypeModalLabel">Edit Leave Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateRecordForm" action="<?= route_to('update_leave') ?>" method="post"> <!-- Use correct form ID -->
                    <?= csrf_field() ?>
                    <!-- Hidden input for l_id -->
                    <input type="hidden" id="edit-l_id" name="l_id"> <!-- Ensure correct ID and name -->

                    <!-- Input for l_name -->
                    <div class="mb-3">
                        <label for="edit-l_name" class="form-label">Name:</label> <!-- Corrected for label association -->
                        <input type="text" class="form-control" id="edit-l_name" name="l_name" maxlength="255" required>
                    </div>

                    <!-- Input for l_description -->
                    <div class="mb-3">
                        <label for="edit-l_description" class="form-label">Description:</label> <!-- Corrected for label association -->
                        <input type="text" class="form-control" id="edit-l_description" name="l_description" maxlength="255" required>
                    </div>

                    <!-- Input for l_days -->
                    <div class="mb-3">
                        <label for="edit-l_days" class="form-label">Number Of Days:</label> <!-- Corrected for label association -->
                        <input type="text" class="form-control" id="edit-l_days" name="l_days" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="3" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Submit button -->
                <button type="button" class="btn btn-primary" onclick="$('#updateRecordForm').submit();">Update</button> <!-- Use jQuery selector to submit -->
            </div>
        </div>
    </div>
</div>



<!-- Include Bootstrap JS if not already included -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/backend/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addRecordForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            var form = $(this);

            $.ajax({
                url: form.attr('action'), // URL from form action attribute
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: true,  // Show the "OK" button
                            confirmButtonText: 'OK'  // Text for the "OK" button
                        }).then(() => {
                            location.reload(); // Reload the page after the "OK" button is clicked
                        });
                        // Optional: reset the form after successful submission
                        form[0].reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                            showConfirmButton: true,  // Show the "OK" button
                            confirmButtonText: 'OK'  // Text for the "OK" button
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while processing your request.',
                        showConfirmButton: true,  // Show the "OK" button
                        confirmButtonText: 'OK'  // Text for the "OK" button
                    });
                }
            });
        });
    });

</script>
<script>
function deleteLeave(l_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this leave type!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '<?= route_to('delete_leave') ?>',  // Adjust this to the route for your deleteLeave function
                data: {
                    l_id: l_id,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>' // Add CSRF token for security in CodeIgniter 4
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success response:', response); // Log the success response for debugging

                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            location.reload(); // Reload page or update table to reflect changes
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message, // Display the error message from the response
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText); // Log the error response text for debugging
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
    document.getElementById('l_days').addEventListener('input', function (e) {
        this.value = this.value.replace(/\D/g, '').substring(0, 3);
    });

    document.getElementById('days').addEventListener('keypress', function (e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });
</script>
<script>
$(document).ready(function() {
    $('.edit-btn').on('click', function() {
        // Get data attributes from clicked button
        var id = $(this).data('id');
        var name = $(this).data('name');
        var description = $(this).data('description');
        var days = $(this).data('days');

        // Set modal input values
        $('#edit-l_id').val(id); // Use correct input ID for hidden input
        $('#edit-l_name').val(name);
        $('#edit-l_description').val(description);
        $('#edit-l_days').val(days);

        // Change modal title to indicate editing
        $('#addLeaveTypeModalLabel').text('Edit Leave Type');
    });
});

</script>
<script>
$(document).ready(function() {
    $('#updateRecordForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var form = $(this); // Reference to the form element

        $.ajax({
            url: form.attr('action'), // URL to send the request to (should match the form action attribute)
            type: 'POST', // HTTP method to use for the request
            data: form.serialize(), // Serialize form data for sending
            dataType: 'json', // Expect JSON response from the server
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload the page after user clicks "OK"
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error details:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while processing your request: ' + error,
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});


</script>



<?= $this->endSection() ?>
