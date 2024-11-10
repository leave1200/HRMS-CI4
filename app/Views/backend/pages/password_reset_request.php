<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<form action="<?= site_url('password/reset-request') ?>" method="POST">
    <label for="email">Enter your email address:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Send Password Reset Link</button>
</form>

<?= $this->endSection()?>
