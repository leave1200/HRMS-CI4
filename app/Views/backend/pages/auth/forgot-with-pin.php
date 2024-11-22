<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>


<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Forgot Password</h2>
							</div>
							<h6 class="mb-20">
								Enter your email address to send Pin code
							</h6>
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
</div>
<div class="col-5">
										<div class="input-group mb-0">
											<a class="btn btn-outline-primary btn-lg btn-block" href="<?= route_to('admin.login.form') ?>">Login</a>
										</div>
									</div>


<?= $this->endSection()?>