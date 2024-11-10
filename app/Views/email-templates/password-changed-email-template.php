
<p>DEAR <?= $mail_data['user']->name ?> </p>
<br>
<p>
   Your password has been successfully changed. Here are your new login Credentials:
   <br><br>
   <b>Login ID: </b> <?= $mail_data['user']->username ?> or <?= $mail_data['user']->email ?>
</p> 
<br><br>
Please, keep your credentials confidentials. Your username and password are your own credentials and you should never share it with anybody else.
<p>
    Madridejos-HR will not be liable for any misuse of your username/email or password
</p>
<br>
----------------------------------------------------------------
<p>
    This email was automatically sent by Madridejos-HR. Do not reply.
</p>