<form method="post" action="/terms/agree">
    <input type="hidden" name="user_id" value="<?= session('user_id') ?>">
    <div>
        <input type="checkbox" name="terms" id="terms" required>
        <label for="terms">I agree to the <a href="/terms-and-conditions" target="_blank">terms and conditions</a></label>
    </div>
    <button type="submit">Agree</button>
</form>
