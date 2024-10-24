<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>
<h2>Please verify you are not a robot</h2>
<form action="<?= esc(route_to('captcha.verify')) ?>" method="POST">
    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    <button type="submit">Verify</button>
</form>

<script src="https://www.google.com/recaptcha/api.js?render=6LfaHGsqAAAAAO2c4GXxqpOPKhxeTRqQ7FkVeF4m"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LfaHGsqAAAAAO2c4GXxqpOPKhxeTRqQ7FkVeF4mY', { action: 'login' }).then(function(token) {
            document.getElementById('recaptcha_token').value = token;
        });
    });
</script>
<?= $this->endSection() ?>