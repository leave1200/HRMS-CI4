<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
    <h3>Hello <?= $mail_data['user']->name ?>,</h3>
    <p>We received a request to reset your password. To reset your password, click the link below:</p>
    <p><a href="<?= $mail_data['actionLink'] ?>">Reset Password</a></p>
    <p>If you did not request this change, you can ignore this email.</p>
    <p>Best Regards,</p>
    <p>Your Application Team</p>
</body>
</html>