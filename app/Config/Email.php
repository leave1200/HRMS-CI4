<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    // public string $fromEmail  = 'markbarsaga121@gmail.com'; // Your Gmail address
    // public string $fromName   = 'Your Name'; // Your preferred name
    // public string $recipients = '';
    public $fromEmail = 'markbarsaga121@gmail.com';
    public $fromName = 'Madridejos-HR';
    public $recipients = '';  // Leave this empty if you are setting this dynamically
    public $SMTPHost = 'smtp.gmail.com';
    public $SMTPUser = 'markbarsaga121@gmail.com';
    public $SMTPPass = 'rjruibhpbthpqdqb'; // Use App Password if 2FA is enabled
    public $SMTPPort = 465;
    public $SMTPTimeout = 60;
    public $SMTPCrypto = 'ssl';
    public $mailType = 'html';
    public $charset = 'utf-8';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = 'ssl://smtp.googlemail.com';

    /**
     * SMTP Username
     */
    public string $SMTPUser = 'markbarsaga121@gmail.com'; // Your Gmail address

    /**
     * SMTP Password
     */
    public string $SMTPPass = 'wfvtoelm pbutkelo'; // Your Gmail password or App password

    /**
     * SMTP Port
     */
    public int $SMTPPort = 465;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'ssl';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'utf-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
