<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="col-md-10">
						<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Reset Password</h2>
							</div>
							<h6 class="mb-20">Enter your new password, confirm and submit</h6>
                            <?php $validation = \Config\Services::validation(); ?>
							<form action="<?= route_to('reset-password-handler', $token) ?>" method="POST">
                                <?= csrf_field(); ?>

                                <?php if(!empty(session()->getFlashdata('success'))) : ?>
                                        <div class="alert alert-success">
                                            <?= session()->getFlashdata('success') ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty(session()->getFlashdata('fail'))) : ?>
                                        <div class="alert alert-danger">
                                            <?= session()->getFlashdata('fail') ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
								<div class="input-group custom">
									<input type="password" class="form-control form-control-lg" placeholder="New Password" name="new_password" value="<?= set_value('new_password') ?>" required>
									<div class="input-group-append custom">
										<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
									</div>
								</div>
                                <?php if($validation->getError('new_password')): ?>
                                    <div class="d-block text-danger" style="margin-top: -25px;margin-bottom:15px;">
                                        <?= $validation->getError('new_password') ?>
                                    </div>
                                    <?php endif; ?>
								<div class="input-group custom">
									<input type="password" class="form-control form-control-lg" placeholder="Confirm New Password" name="confirm_new_password" value="<?= set_value('confirm_new_password') ?>" required>
									<div class="input-group-append custom">
										<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
									</div>
								</div>
                                <?php if($validation->getError('confirm_new_password')): ?>
                                    <div class="d-block text-danger" style="margin-top: -25px;margin-bottom:15px;">
                                        <?= $validation->getError('confirm_new_password') ?>
                                    </div>
                                    <?php endif; ?>
								<div class="row align-items-center">
									<div class="col-5">
										<div class="input-group mb-0">
											
											<!-- use code for form submit -->
											<input class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">
										
											<!-- <a class="btn btn-primary btn-lg btn-block" href="index.html">Submit</a> -->
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword'); // Ensure you have an element with this ID
        const newPasswordField = document.getElementById('new_password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                // Toggle the password field type between text and password
                if (newPasswordField.type === "password") {
                    newPasswordField.type = "text"; // Show password
                } else {
                    newPasswordField.type = "password"; // Hide password
                }
            });
        }
    });
</script>

</script>

<?= $this->endSection()?>