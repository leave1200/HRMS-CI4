<h2>Please verify you are not a robot</h2>
<form action="<?= esc(route_to('captcha.verify')) ?>" method="POST">
    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    <button type="submit">Verify</button>
</form>

<script src="https://www.google.com/recaptcha/api.js?render=YOUR_SITE_KEY"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('YOUR_SITE_KEY', { action: 'homepage' }).then(function(token) {
            document.getElementById('recaptcha_token').value = token;
        });
    });
</script>
