<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>
<div class="login-box bg-white box-shadow border-radius-10">
<div class="container">
        <h2 class="mt-5">Reset Password</h2>

        <!-- Display validation errors -->
        <form action="<?= route_to('reset-password-handler-with-pin') ?>" method="POST">
        <?= csrf_field(); ?>
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
        <div class="row align-items-center">
									<div class="col-5">
										<div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary">Reset Password</button>
										</div>
									</div>
									<div class="col-2">
										<div class="font-16 weight-600 text-center" data-color="#707373" style="color: rgb(112, 115, 115);">
											OR
										</div>
									</div>
									<div class="col-5">
										<div class="input-group mb-0">
											<a class="btn btn-outline-primary btn-lg btn-block" href="<?= route_to('admin.login.form') ?>">Cancel</a>
										</div>
									</div>
								</div>
        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection()?>