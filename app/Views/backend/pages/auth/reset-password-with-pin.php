<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="mt-5">Reset Password with Pin</h2>

    <!-- Display success or error message -->
    <?php if(session()->getFlashdata('fail')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('fail') ?></div>
    <?php endif; ?>

    <!-- Pin verification form -->
    <form action="<?= route_to('verify-pin') ?>" method="POST" id="pinForm">
        <?= csrf_field(); ?>

        <div class="form-group">
            <label for="pin">Enter Pin Code</label>
            <div id="pinInputs" class="d-flex gap-2">
                <!-- Six input fields for the PIN -->
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" name="pin[]" maxlength="1" 
                           class="form-control text-center pin-input" 
                           style="width: 50px;" required>
                <?php endfor; ?>
            </div>
            <small class="text-danger"><?= isset($validation) ? $validation->getError('pin') : '' ?></small>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Verify Pin</button>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pinInputs = document.querySelectorAll('.pin-input');

    // Focus on the next input after entering a digit
    pinInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < pinInputs.length - 1) {
                pinInputs[index + 1].focus();
            }
        });

        // Move back to the previous input on backspace
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                pinInputs[index - 1].focus();
            }
        });
    });

    // Automatically focus on the first input field when the page loads
    pinInputs[0].focus();
});
</script>

<?= $this->endSection() ?>

