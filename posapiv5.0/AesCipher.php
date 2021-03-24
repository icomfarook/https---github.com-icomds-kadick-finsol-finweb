<?php

class AesCipher {
    
    private const OPENSSL_CIPHER_NAME = "aes-128-cbc";
    private const CIPHER_KEY_LEN = 16; //128 bits

    private static function fixKey($key) {
        
        if (strlen($key) < AesCipher::CIPHER_KEY_LEN) {
            //0 pad to len 16
            return str_pad("$key", AesCipher::CIPHER_KEY_LEN, "0"); 
        }
        
        if (strlen($key) > AesCipher::CIPHER_KEY_LEN) {
            //truncate to 16 bytes
            return substr($key, 0, AesCipher::CIPHER_KEY_LEN); 
        }

        return $key;
    }

    /**
    * Encrypt data using AES Cipher (CBC) with 128 bit key
    * 
    * @param type $key - key to use should be 16 bytes long (128 bits)
    * @param type $data - data to encrypt
    * @return encrypted data in base64 encoding with iv attached at end after a :
    */
    static function encrypt($key, $data) {

        $iv = openssl_random_pseudo_bytes(16);
        //$encodedEncryptedData = base64_encode(openssl_encrypt($data, AesCipher::OPENSSL_CIPHER_NAME, AesCipher::fixKey($key), OPENSSL_RAW_DATA, $iv));
        $encodedEncryptedData = base64_encode(openssl_encrypt($data, AesCipher::OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, $iv));
        $encodedIV = base64_encode($iv);
        $encryptedPayload = $encodedEncryptedData.":".$encodedIV;

        return $encryptedPayload;
    }

    /**
    * Decrypt data using AES Cipher (CBC) with 128 bit key
    * 
    * @param type $key - key to use should be 16 bytes long (128 bits)
    * @param type $data - data to be decrypted in base64 encoding with iv attached at the end after a :
    * @return decrypted data
    */
    static function decrypt($key, $data) {

        $parts = explode(':', $data); //Separate Encrypted data from iv.
        $encrypted = $parts[0];
        $iv = $parts[1];
        //$decryptedData = openssl_decrypt(base64_decode($encrypted), AesCipher::OPENSSL_CIPHER_NAME, AesCipher::fixKey($key), OPENSSL_RAW_DATA, base64_decode($iv));
        $decryptedData = openssl_decrypt(base64_decode($encrypted), AesCipher::OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, base64_decode($iv));

        return $decryptedData;
    }
};

/*
$input_str = "ansari~ansari~1122334455";
$key_str = "0123456789abcdef";
echo "input_str = ".$input_str."\n";
$encrypted = AesCipher::encrypt($key_str, $input_str);
echo "encryted = ".$encrypted."\n";
var_dump($encrypted);
*/


//$decrypted1 = AesCipher::decrypt('0092513805172020', 'DUTbKyyx6wrB8A8gf+Z+jsm+pQEIuLCyr4fm922qRwb4K8C/uYiDwb7FMBMA8UXF:ODFiNWM4MTZmMzRmN2NkYw==');
//echo "decrypted1 = ".$decrypted1."\n";
//var_dump($decrypted1);
//$decrypted = AesCipher::decrypt($key_str, $encrypted);
//echo "decrypted = ".$decrypted."\n";
//var_dump($decrypted);



?>