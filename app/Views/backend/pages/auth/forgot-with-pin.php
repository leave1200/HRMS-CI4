<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<form action="<?= route_to('send-pin-code') ?>" method="POST">
    <?= csrf_field(); ?>

    <?php if (!empty(session()->getFlashdata('success'))): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div class="input-group custom">
        <input type="email" class="form-control" placeholder="Enter your email" name="email">
        <div class="input-group-append">
            <span class="input-group-text"><i class="fa fa-envelope-o"></i></span>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Send Pin</button>
</form>


<?= $this->endSection()?>