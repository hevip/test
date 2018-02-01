<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/31
 * Time: 下午3:27
 */
function rsaDecryptData($ciphertext)
{
    $rsa_private_key = config('rsa_private_key');
    $cleartext = '';
    //判断密文长度，按照128字节分割，解密
    foreach (str_split(base64_decode($ciphertext), 128) as $chunk) {

        openssl_private_decrypt($chunk, $decryptData, $rsa_private_key);

        $cleartext .= $decryptData;
    }
    //openssl_private_decrypt(base64_decode($ciphertext), $cleartext, $rsa_private_key);
    if (!empty($cleartext)) {
        return $cleartext;
    } else {
        return false;
    }
}