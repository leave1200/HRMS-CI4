<p>Hello <?= esc($mail_data['user']->name) ?>,</p>
<p>We received a request to reset your password. Click the link below to reset your password:</p>
<p><a href="<?= esc($mail_data['actionLink']) ?>">Reset Password</a></p>
<p>If you did not request a password reset, please ignore this email.</p>
