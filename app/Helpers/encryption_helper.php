<?php
// app/Helpers/encryption_helper.php

function encryptData($data, $key, $iv) {
    return openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
}

function decryptData($encryptedData, $key, $iv) {
    return openssl_decrypt($encryptedData, 'AES-256-CBC', $key, 0, $iv);
}
