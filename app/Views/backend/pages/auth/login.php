<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Login To HRMS</h2>
    </div>

    <?php $validation = \Config\Services::validation(); ?>

    <form action="<?= esc(route_to('admin.login.handler'), 'attr') ?>" method="POST">
        <?= csrf_field() ?> <!-- Ensuring CSRF protection is in place -->

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
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
        <!-- Input for Username or Email -->
        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" placeholder="Username or Email" name="login_id" value="<?= esc(set_value('login_id')) ?>">
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
            <input type="password" class="form-control form-control-lg" placeholder="**********" name="password" value="<?= esc(set_value('password')) ?>">
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
                <div class="forgot-password">
                    <a href="<?= esc(route_to('admin.forget.form'), 'attr') ?>">Forgot Password</a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                <!-- <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In" onclick="submitForm()"> -->
                <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LdcqXoqAAAAAOKpdE7btEsLljApBKTl9gLoj5Ct"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LdcqXoqAAAAAOKpdE7btEsLljApBKTl9gLoj5Ct', { action: 'login' }).then(function(token) {
            document.getElementById('recaptcha_token').value = token;
        });
    });
</script>

<?= $this->endSection() ?>
