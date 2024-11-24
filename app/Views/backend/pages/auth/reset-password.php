<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="container">
        <h2 class="mt-5">Reset Password</h2>

        <!-- Display validation errors -->
        <?= form_open(route_to('reset-password-handler-with-pin')) ?>
        <input type="hidden" name="pin" value="<?= esc($pin) ?>">

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
            <small class="text-danger"><?= isset($validation) ? $validation->getError('new_password') : '' ?></small>
        </div>

        <div class="form-group">
            <label for="confirm_new_password">Confirm New Password</label>
            <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" required>
            <small class="text-danger"><?= isset($validation) ? $validation->getError('confirm_new_password') : '' ?></small>
        </div>

        <button type="submit" class="btn btn-primary">Reset Password</button>
        <?= form_close() ?>
    </div>

<?= $this->endSection()?>