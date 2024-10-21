<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h1><?= esc($pageTitle) ?></h1>
</div>

<div class="table-responsive">
    <table id="approvedLeavesTable" class="table table-striped table-bordered">
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
            <?php if (!empty($leaveApplications)): ?>
                <?php foreach ($leaveApplications as $application): ?>
                    <tr>
                        <td><?= esc($application['la_id']) ?></td>
                        <td><?= esc($application['employee_name']) ?></td>
                        <td><?= esc($application['leave_type_name']) ?></td>
                        <td><?= esc($application['la_start']) ?></td>
                        <td><?= esc($application['la_end']) ?></td>
                        <td><?= esc($application['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No approved leave applications found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#approvedLeavesTable').DataTable({
        responsive: true,
    });
});
</script>

<?= $this->endSection() ?>
