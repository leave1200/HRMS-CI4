<?= $this->extend('backend/layouts/auth') ?>

<?= $this->section('content') ?>

<div class="terms-container">
    <h2>Terms and Conditions</h2>
    <p>Please read and agree to the following terms and conditions to proceed:</p>
    
    <div class="terms-content">
        <!-- Display your terms and conditions content here -->
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse tincidunt eros a nisl cursus, eget tincidunt lectus auctor. Curabitur commodo dolor id nulla cursus aliquet.</p>
    </div>

    <form method="post" action="<?= route_to('terms.agree') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="user_id" value="<?= session('user_id') ?>">
        <div class="form-check">
            <input type="checkbox" name="terms" id="terms" value="agreed" required>
            <label for="terms">I agree to the terms and conditions.</label>
        </div>
        <button type="submit" class="btn btn-primary">Agree and Continue</button>
    </form>
</div>

<?= $this->endSection() ?>
