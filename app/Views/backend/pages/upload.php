<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h2><?= esc($pageTitle); ?></h2>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= route_to('admin.home'); ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Manage Uploads
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Display Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <!-- File Upload Form -->
    <form action="<?= route_to('uploadFile') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="file">Choose File to Upload:</label>
            <input type="file" name="file" id="file" class="form-control" required 
                accept=".doc,.docx,.csv,.xls,.xlsx" onchange="validateFileType(this)">
            <button type="submit" class="btn btn-primary mt-3"><i class="icon-copy fi-upload-cloud">Upload</i></button>
        </div>
    </form>

    <!-- Table to show uploaded files -->
    <div class="pd-20 card-box mb-30">
        <div class="table-responsive">
            <table id="uploadsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Owner</th>
                        <th>File Name</th>
                        <th>Original Name</th>
                        <th>Upload Date</th>
                        <?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE' && $userStatus !== 'STAFF'): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($files)): ?>
                        <?php foreach ($files as $index => $file): ?>
                            <tr>
                                <td><?= esc($index + 1) ?></td>
                                <?php if (isset($userStatus) && $userStatus === 'ADMIN'): ?>
                                    <!-- If user is Admin, get the username from the users table -->
                                    <td><?= esc($file['username']) ?></td>
                                <?php else: ?>
                                    <!-- If user is Employee or Staff, get the username from the session -->
                                    <td><?= esc(session()->get('username')) ?></td>
                                <?php endif; ?>
                                <td><?= esc($file['name']) ?></td>
                                <td><?= esc($file['original_name']) ?></td>
                                <td><?= esc($file['uploaded_at']) ?></td>
                                <td>
                                    
                                    <!-- Download Link -->
                                    <a href="<?= route_to('downloadFile', $file['id']) ?>" class="btn btn-success btn-sm"><i class="icon-copy fi-save">Download</i></a>

                                    
                                        <!-- Delete Action (using SweetAlert for confirmation) -->
                                         <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= esc($file['id']) ?>)"><i class="icon-copy fi-page-delete">Delete</i></button>

                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No files uploaded yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Load jQuery, DataTables, and SweetAlert2 -->

<script>
    $(document).ready(function() {
        $('#uploadsTable').DataTable({
            responsive: true
        });
    });
</script>
<script>
    function confirmDelete(fileId) {
        // var deleteUrl = "<?= base_url('delete-file') ?>" + "/" + fileId;

        // Swal.fire({
        //     title: 'Are you sure?',
        //     text: "This action cannot be undone!",
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, delete it!',
        //     cancelButtonText: 'Cancel'
        // }).then((result) => {
        //     if (result.isConfirmed) {

        //         // Show success message after deletion
        //         Swal.fire({
        //             icon: 'success',
        //             title: 'Deleted!',
        //             text: 'Your file has been deleted successfully.',
        //             confirmButtonText: 'OK'
        //         }).then(() => {
        //                     // Reload the page
        //                     window.location.href = deleteUrl;
        //         });
        //     }
        // });
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
                url: '<?= route_to('deleteFile') ?>',
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
    document.getElementById('file').addEventListener('change', function(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];

        // 20MB in bytes
        const maxSize = 10 * 1024 * 1024; // 20MB

        if (file && file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'The file size must not exceed 10MB.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear the file input field if the file is too large
                fileInput.value = ''; 
            });
        }
    });
</script>
<script>
function validateFileType(input) {
    const allowedExtensions = /(\.doc|\.docx|\.csv|\.xls|\.xlsx)$/i;
    const filePath = input.value;

    // Check if the file extension is valid
    if (!allowedExtensions.exec(filePath)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid file type',
            text: 'Please upload a .doc, .docx, .csv, .xls, or .xlsx file.',
            confirmButtonText: 'OK'
        });
        input.value = ''; // Clear the input
    }
}
</script>

<?= $this->endSection() ?>
