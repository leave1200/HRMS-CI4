<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="container">
        <h2 class="mt-5">Reset Password with Pin</h2>

        <!-- Display success or error message -->
        <?php if(session()->getFlashdata('fail')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('fail') ?></div>
        <?php endif; ?>

        <!-- Pin verification form -->
        <?= form_open(route_to('admin.reset-password-handler-with-pin')) ?>
        <input type="hidden" name="pin" value="<?= esc($pin) ?>">

        <div class="form-group">
            <label for="pin">Enter Pin Code</label>
            <input type="text" name="pin" id="pin" class="form-control" value="<?= old('pin', $pin) ?>" required>
            <small class="text-danger"><?= isset($validation) ? $validation->getError('pin') : '' ?></small>
        </div>

        <button type="submit" class="btn btn-primary">Verify Pin</button>
        <?= form_close() ?>
    </div>

<?= $this->endSection()?>