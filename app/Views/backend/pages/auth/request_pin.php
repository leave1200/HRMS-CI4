<?= $this->extend('backend/layouts/main') ?>

<?= $this->section('content') ?>

<h3><?= esc($pageTitle) ?></h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('fail')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('fail') ?>
    </div>
<?php endif; ?>

<form action="<?= route_to('send-pin-code') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
    </div>

    <?php if (isset($validation) && $validation->hasError('email')): ?>
        <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary btn-block">Request PIN</button>
</form>

<?= $this->endSection() ?>
