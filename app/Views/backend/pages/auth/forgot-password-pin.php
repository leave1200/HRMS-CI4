<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="container">
        <h1>Enter Pin Code</h1>
        <?php if (session()->has('fail')): ?>
            <div class="alert alert-danger"><?= session('fail') ?></div>
        <?php endif; ?>
        <form action="<?= route_to('resetPasswordWithPin') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="pin">Enter the Pin Code</label>
                <input type="text" id="pin" name="pin" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify Pin</button>
        </form>
    </div>

<?= $this->endSection()?>