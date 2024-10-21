<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12">
            <h4>Approved Leave Applications</h4>
            <table id="approvedLeavesTable" class="table table-striped table-bordered" style="width:100%">
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
                    <?php foreach ($approvedLeaves as $leave): ?>
                        <tr>
                            <td><?= esc($leave['la_id']) ?></td>
                            <td><?= esc($leave['employee_name']) ?></td>
                            <td><?= esc($leave['leave_type_name']) ?></td>
                            <td><?= esc($leave['la_start']) ?></td>
                            <td><?= esc($leave['la_end']) ?></td>
                            <td><?= esc($leave['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize the approved leaves DataTable
    $('#approvedLeavesTable').DataTable({
        responsive: true,
    });
});
</script>

<?= $this->endSection() ?>
