<p>Hi <?= esc($mail_data['name']) ?>,</p>
    <p>Your password reset pin code is: <strong><?= esc($mail_data['pin_code']) ?></strong></p>
    <p>You can reset your password by using this pin code or by clicking the link below:</p>
    <p>
        <a href="<?= esc($mail_data['reset_link']) ?>" target="_blank">Reset Password</a>
    </p>
    <p>This link will expire in 15 minutes.</p>
    <p>Thank you,<br>Your Team</p>