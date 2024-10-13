<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Login To HRMS</h2>
    </div>

    <?php $validation = \Config\Services::validation(); ?>
    
    <form action="<?= esc(route_to('admin.login.handler'), 'attr') ?>" method="POST" onsubmit="return validateForm()">
        <?= csrf_field() ?> <!-- Ensuring CSRF protection is in place -->

        <!-- Success flash message -->
        <?php if (!empty(session()->getFlashdata('success'))) : ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?> <!-- Escaping output to prevent XSS -->
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Failure flash message -->
        <?php if (!empty(session()->getFlashdata('fail'))) : ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('fail')) ?> <!-- Escaping output to prevent XSS -->
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Input for Username or Email -->
        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" id="username_email" placeholder="Username or Email" name="login_id" value="<?= esc(set_value('login_id')) ?>" required <?= (session()->get('login_attempts') >= 3) ? 'disabled' : '' ?>>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        
        <!-- Validation Error for login_id -->
        <?php if ($validation->getError('login_id')): ?>
            <div class="d-block text-danger" style="margin-top: 25px; margin-bottom: 15px;">
                <?= esc($validation->getError('login_id')) ?> <!-- Escaping error output -->
            </div>
        <?php endif; ?>

        <!-- Input for Password -->
        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" id="password" placeholder="**********" name="password" value="<?= esc(set_value('password')) ?>" required <?= (session()->get('login_attempts') >= 3) ? 'disabled' : '' ?>>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>

        <!-- Validation Error for password -->
        <?php if ($validation->getError('password')): ?>
            <div class="d-block text-danger" style="margin-top: 25px; margin-bottom: 15px;">
                <?= esc($validation->getError('password')) ?> <!-- Escaping error output -->
            </div>
        <?php endif; ?>

        <div class="row pb-30">
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember"> <!-- Add 'name' attribute for 'remember me' -->
                    <label class="custom-control-label" for="customCheck1">Remember</label>
                </div>
            </div>
            <div class="col-6">
                <div class="forgot-password">
                    <a href="<?= esc(route_to('admin.forget.forms'), 'attr') ?>">Forgot Password</a> <!-- Escaping URL -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In" <?= (session()->get('login_attempts') >= 3) ? 'disabled' : '' ?>>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Client-side validation to prevent scripting and file injection
function validateForm() {
    const loginId = document.querySelector('input[name="login_id"]').value;
    const password = document.querySelector('input[name="password"]').value;

    // Regex patterns to block scripts and malicious input
    const scriptPattern = /<script.*?>.*?<\/script>/i; // Matches <script> tags
    const htmlPattern = /<\/?[a-z][\s\S]*>/i; // Matches HTML tags

    if (scriptPattern.test(loginId) || htmlPattern.test(loginId)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid input',
            text: 'Input contains invalid characters.',
        });
        return false; // Prevent form submission
    }

    if (scriptPattern.test(password) || htmlPattern.test(password)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid input',
            text: 'Input contains invalid characters.',
        });
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}

// Timer and lockout script
if (<?= json_encode(session()->get('login_attempts') >= 3) ?>) {
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = 180; // 3 minutes in seconds
        const timerHtml = `Too many incorrect attempts. Please wait for <strong><span id="countdown-timer">3:00</span></strong> before trying again.`;
        
        // Display SweetAlert for lockout message
        Swal.fire({
            icon: 'warning',
            title: 'Account Locked',
            html: timerHtml,
            showConfirmButton: false, // No confirm button
            allowOutsideClick: false // Prevent closing the alert by clicking outside
        });

        const interval = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            // Update countdown timer in SweetAlert
            document.getElementById('countdown-timer').textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            if (timeLeft <= 0) {
                clearInterval(interval);
                // Enable input fields and button after lockout period
                document.getElementById('username_email').disabled = false;
                document.getElementById('password').disabled = false;
                document.querySelector('input[type="submit"]').disabled = false;
                Swal.close(); // Close the SweetAlert
            }
        }, 1000);
    });
}
</script>

<?= $this->endSection() ?>
