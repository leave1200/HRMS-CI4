<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<form action="<?= route_to('reset-password-handler-pin') ?>" method="POST">
    <?= csrf_field(); ?>

    <input type="hidden" name="pin" value="<?= $pin ?>">

    <div class="input-group custom">
        <input type="password" class="form-control" placeholder="New Password" name="new_password">
    </div>
    <div class="input-group custom">
        <input type="password" class="form-control" placeholder="Confirm New Password" name="confirm_new_password">
    </div>

    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>


<?= $this->endSection()?>
