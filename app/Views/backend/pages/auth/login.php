<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Login To HRMS</h2>
    </div>

    <?php $validation = \Config\Services::validation(); ?>

    <form action="<?= esc(route_to('admin.login.handler'), 'attr') ?>" method="POST" id="loginForm">
        <?= csrf_field() ?> <!-- CSRF Protection -->

        <!-- Success flash message -->
        <?php if (!empty(session()->getFlashdata('success'))) : ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Failure flash message -->
        <?php if (!empty(session()->getFlashdata('fail'))) : ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('fail')) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- System Inaccessible Alert -->
        <?php if (session()->get('system_accessible') === false): ?>
            <script>
                swal({
                    title: "System Unavailable!",
                    text: "The system is currently inaccessible. Please try again later.",
                    icon: "warning",
                    button: "OK"
                });
            </script>
        <?php endif; ?>

        <!-- reCAPTCHA v2 widget (above email input) -->
        <div class="g-recaptcha" data-sitekey="your-recaptcha-site-key" id="recaptcha-widget" data-callback="enableFormFields" data-size="compact"></div>

        <!-- Input for Username or Email -->
        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" placeholder="Username or Email" name="login_id" value="<?= esc(set_value('login_id')) ?>" id="login_id" disabled>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        
        <!-- Validation Error for login_id -->
        <?php if ($validation->getError('login_id')): ?>
            <div class="d-block text-danger" style="margin-top: 25px; margin-bottom: 15px;">
                <?= esc($validation->getError('login_id')) ?>
            </div>
        <?php endif; ?>

        <!-- Input for Password -->
        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" placeholder="**********" name="password" value="<?= esc(set_value('password')) ?>" id="password" disabled>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>

        <!-- Validation Error for password -->
        <?php if ($validation->getError('password')): ?>
            <div class="d-block text-danger" style="margin-top: 25px; margin-bottom: 15px;">
                <?= esc($validation->getError('password')) ?>
            </div>
        <?php endif; ?>

        <div class="row pb-30">
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember" disabled>
                    <label class="custom-control-label" for="customCheck1">Remember</label>
                </div>
            </div>
            <div class="col-6">
                <div class="forgot-password">
                    <a href="<?= esc(route_to('admin.forgot.form'), 'attr') ?>" id="forgot-password-link" disabled>Forgot Password</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In" id="submit-btn" disabled>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Include reCAPTCHA API -->
<script src="https://www.google.com/recaptcha/api.js?6Lcmo3oqAAAAAFqJs_b1FqTpWL_sjT2dTSffP1Gt" async defer></script>

<script>
    // Function to enable form fields once reCAPTCHA is verified
    function enableFormFields() {
        document.getElementById('login_id').disabled = false;
        document.getElementById('password').disabled = false;
        document.getElementById('submit-btn').disabled = false;
        document.getElementById('forgot-password-link').disabled = false;
        document.getElementById('customCheck1').disabled = false;  // Enable the 'remember me' checkbox
    }
</script>

<?= $this->endSection() ?>
