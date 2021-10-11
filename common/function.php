<?php

/* 
 * key 암호화
 * @param str string
 * @return string
 */
function getKeyEncrypt($str)
{
    $key = hash('sha256', 'secret_key');
    $iv = substr(hash('sha256', 'secret_iv'), 0, 16);
    return base64_encode(openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv));
}

/*
 * key 복호화
 * @param str string
 * @return string
*/
function getKeyDecrypt($str)
{
    $key = hash('sha256', 'secret_key');
    $iv = substr(hash('sha256', 'secret_iv'), 0, 16);
    return openssl_decrypt(base64_decode($str), "AES-256-CBC", $key, 0, $iv);
}
