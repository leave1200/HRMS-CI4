<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="container">
        <h2 class="mt-5">Forgot Password with Pin</h2>

        <!-- Display success or error message -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('fail')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('fail') ?></div>
        <?php endif; ?>

        <!-- Email input form -->
        <?= form_open(route_to('admin.send-pin')) ?>
        <div class="form-group">
            <label for="email">Enter your email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" required>
            <small class="text-danger"><?= isset($validation) ? $validation->getError('email') : '' ?></small>
        </div>

        <button type="submit" class="btn btn-primary">Send Pin</button>
        <?= form_close() ?>
    </div>

<?= $this->endSection()?>