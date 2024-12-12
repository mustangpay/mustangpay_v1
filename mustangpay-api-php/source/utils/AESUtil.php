<?php

namespace app\utils;

use Exception;

class AESUtil
{
    private const KEY_ALGORITHM = 'AES';
    private const CIPHER_ALGORITHM = 'aes-128-gcm'; // AES with GCM mode

    /**
     * AES Encryption operation
     *
     * @param string $content The content to be encrypted
     * @param string $password The encryption password
     * @return string|null The encrypted data in Base64 format
     */
    public static function encrypt($content, $password)
    {
        try {
            // Generate a 128-bit AES key from the password
            $key = self::getSecretKey($password);

            // Initialize cipher method (AES-128-GCM)
            $cipher = "aes-128-gcm";

            // Generate a 12-byte initialization vector (IV)
            $iv = random_bytes(12);

            // Encrypt the data
            $ciphertext = openssl_encrypt($content, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);

            // Combine the IV, ciphertext, and tag into one string (Base64 encoded)
            $encryptedData = base64_encode($iv . $ciphertext . $tag);

            return $encryptedData;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * AES Decryption operation
     *
     * @param string $base64Content The encrypted content in Base64 format
     * @param string $password The decryption password
     * @return string|null The decrypted content
     */
    public static function decrypt($base64Content, $password)
    {
        try {
            // Decode the Base64 encoded data
            $data = base64_decode($base64Content);

            // Extract the IV, ciphertext, and tag from the combined data
            $iv = substr($data, 0, 12);
            $ciphertext = substr($data, 12, -16);
            $tag = substr($data, -16);

            // Generate the AES key
            $key = self::getSecretKey($password);

            // Initialize cipher method (AES-128-GCM)
            $cipher = "aes-128-gcm";

            // Decrypt the data
            $decryptedData = openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);

            return $decryptedData;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Generate the secret key for AES encryption/decryption
     *
     * @param string $password The password used to generate the key
     * @return string The AES key
     */
    private static function getSecretKey($password)
    {
        return substr(openssl_digest(openssl_digest($password, 'sha1', true), 'sha1', true), 0, 16);
    }
}

