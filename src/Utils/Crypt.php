<?php

declare(strict_types=1);

namespace App\Utils;

class Crypt
{
    /**
     * Encrypts data with the supplied passphrase, using AES-256-CTR.
     * 
     * @param string $plaintext the plaintext data.
     * @param string $passphrase a passphrase/password.
     * @return string|false encrypted data: iv + ciphertext or `false` on error.
     */
    public static function encryptWithPublicKey(string $plaintext, string $passphrase)
    {
        openssl_public_encrypt($plaintext, $encSymKey, $passphrase);
        return base64_encode($encSymKey);
    }

    /**
     * Encrypts data with the supplied passphrase, using AES-256-GCM and PBKDF2-SHA256.
     * 
     * @param string $plaintext the plaintext data.
     * @param string $passphrase a passphrase/password.
     * @return string|false encrypted data: salt + nonce + ciphertext + tag or `false` on error.
     */
    public static function encrypt(string $plaintext, string $passphrase)
    {
        $salt = openssl_random_pseudo_bytes(16);
        $nonce = openssl_random_pseudo_bytes(12);
        $key = hash_pbkdf2("sha256", $passphrase, $salt, 40000, 32, true);
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, 1, $nonce, $tag);

        return base64_encode($salt . $nonce . $ciphertext . $tag);
    }

    /**
     * Decrypts data with the supplied passphrase, using AES-256-GCM and PBKDF2-SHA256.
     * 
     * @param string $ciphertext encrypted data.
     * @param string $passphrase a passphrase/password.
     * @return string|false plaintext data or `false` on error.
     */
    public static function decrypt(string $ciphertext, string $passphrase)
    {
        $input = base64_decode($ciphertext);
        $salt = substr($input, 0, 16);
        $nonce = substr($input, 16, 12);
        $ciphertext = substr($input, 28, -16);
        $tag = substr($input, -16);
        $key = hash_pbkdf2("sha256", $passphrase, $salt, 40000, 32, true);

        return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, 1, $nonce, $tag);
    }
}
