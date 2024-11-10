
<p>DEAR <?= $mail_data['user']->name ?> </p>
<p>
    We have receive a request to reset your password for HRMO-LAWIS account associated with <i> <?= $mail_data['user']->email ?> </i>
    You can reset your password by clicking the button bellow:
    <a href="<?= $mail_data['actionLink'] ?>" style="color:#fff;border-color:#22bc66;border-style:solid;
    border-width:5px 10px;background-color:#22bc66;display:inline-block;text-decoration:none;border-radius:3px;box-shadow:o 2px rgba(0,0,0,0.16);-webkit-text-size-adjust:none;box-sizing:border-box;" target="_blank">Reset Password</a>
    <br><br>
    <b>NB:</b> This link will still valid within 15 minutes.
    <br><br>
    If you dont request for password reset, please ignore this message.

</p> 