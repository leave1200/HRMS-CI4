<!-- app/Views/password_reset.php -->
<form action="<?= site_url('password/reset') ?>" method="POST">
    <input type="hidden" name="token" value="<?= $token ?>" />
    <label for="password">New Password:</label>
    <input type="password" name="password" id="password" required>
    <button type="submit">Reset Password</button>
</form>
