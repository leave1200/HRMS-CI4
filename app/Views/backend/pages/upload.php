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
                            Employee List
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
            <input type="file" name="file" id="file" class="form-control" required>
            <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </div>
    </form>

    <!-- Table to show uploaded files -->
    <?php if (isset($userStatus) && $userStatus !== 'EMPLOYEE'): ?>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($files)): ?>
                            <?php foreach ($files as $index => $file): ?>
                                <tr>
                                    <td><?= esc($index + 1) ?></td>
                                    <td><?= esc(session()->get('username')) ?></td>
                                    <td><?= esc($file['name']) ?></td>
                                    <td><?= esc($file['original_name']) ?></td>
                                    <td><?= esc($file['uploaded_at']) ?></td>
                                    <td>
                                        <!-- View Inline (for viewable file types) -->
                                        <a href="<?= route_to('viewFile', $file['id']) ?>" class="btn btn-info btn-sm" target="_blank">View</a>
                                        
                                        <!-- Download Link -->
                                        <a href="<?= route_to('downloadFile', $file['id']) ?>" class="btn btn-success btn-sm">Download</a>

                                        <?php if (session()->get('userStatus') === 'ADMIN'): ?>
                                            <!-- Delete Action (only for admins) -->
                                            <a href="<?= route_to('deleteFile', $file['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a>
                                        <?php endif; ?>
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
    </div><?php endif; ?>
    <?php if (isset($userStatus) && $userStatus !== 'ADMIN'): ?>
        <div class="pd-20 card-box mb-30">
                <div class="table-responsive">
                    <table id="uploadsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Owner</th>
                                <th>File Name</th>
                                <th>Upload Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($files)): ?>
                                <?php foreach ($files as $index => $file): ?>
                                    <tr>
                                        <td><?= esc($index + 1) ?></td>
                                        <td><?= esc(session()->get('username')) ?></td>
                                        <td><?= esc($file['name']) ?></td>
                                        <td><?= esc($file['uploaded_at']) ?></td>
                                        <td>
                                            <!-- View Inline (for viewable file types) -->
                                            <a href="<?= route_to('viewFile', $file['id']) ?>" class="btn btn-info btn-sm" target="_blank">View</a>
                                            
                                            <!-- Download Link -->
                                            <a href="<?= route_to('downloadFile', $file['id']) ?>" class="btn btn-success btn-sm">Download</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No files uploaded yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
</div>

<!-- Load jQuery, DataTables, and SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#uploadsTable').DataTable({
            responsive: true
        });

        // Check if a success message is present in the session
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Uploaded!',
                text: 'Your file has been uploaded successfully.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reset the form to clear the file input
                $('form')[0].reset();
            });
        <?php endif; ?>
    });
</script>

<?= $this->endSection() ?>
