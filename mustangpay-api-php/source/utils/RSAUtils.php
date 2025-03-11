<?php

namespace app\utils;

use Yii;

class RSAUtils
{

    // Define constants
    const RSA_ALGORITHM = 'RSA';
    const SIGNATURE_ALGORITHM = OPENSSL_ALGO_SHA256;

    // Generate public and private key pair
    public static function createKeys($keySize)
    {
        // Generate key pair using OpenSSL
        $config = array(
            "private_key_bits" => $keySize,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];

        // Encode to Base64 URL Safe
        $publicKeyStr = self::base64UrlEncode($publicKey);
        $privateKeyStr = self::base64UrlEncode($privateKey);

        return [
            "publicKey" => $publicKeyStr,
            "privateKey" => $privateKeyStr
        ];
    }

    // Encrypt with public key
    public static function publicEncrypt($data, $publicKey)
    {
        $publicKeyObj = self::getPublicKey($publicKey);
        openssl_public_encrypt($data, $encryptedData, $publicKeyObj);
        return self::base64UrlEncode($encryptedData);
    }

    // Decrypt with private key
    public static function privateDecrypt($data, $privateKey)
    {
        $privateKeyObj = self::getPrivateKey($privateKey);
        openssl_private_decrypt(self::base64UrlDecode($data), $decryptedData, $privateKeyObj);
        return $decryptedData;
    }

    // Encrypt with private key
    public static function privateEncrypt($data, $privateKey)
    {
        $privateKeyObj = self::getPrivateKey($privateKey);
        openssl_private_encrypt($data, $encryptedData, $privateKeyObj);
        return self::base64UrlEncode($encryptedData);
    }

    // Decrypt with public key
    public static function publicDecrypt($data, $publicKey)
    {
        $publicKeyObj = self::getPublicKey($publicKey);
        openssl_public_decrypt(self::base64UrlDecode($data), $decryptedData, $publicKeyObj);
        return $decryptedData;
    }

    // Generate signature with private key
    public static function sign($data, $privateKey)
    {
        $privateKeyObj = self::getPrivateKey($privateKey);
        openssl_sign($data, $signature, $privateKeyObj, self::SIGNATURE_ALGORITHM);
        return self::base64UrlEncode($signature);
    }

    // Verify signature with public key
    public static function verify($data, $publicKey, $sign)
    {
        $publicKeyObj = self::getPublicKey($publicKey);
        $signature = self::base64UrlDecode($sign);
        return openssl_verify($data, $signature, $publicKeyObj, self::SIGNATURE_ALGORITHM) === 1;
    }

    // Get public key from Base64 string
    private static function getPublicKey($publicKey)
    {
        $publicKeyPem = "-----BEGIN PUBLIC KEY-----\n" . $publicKey . "\n-----END PUBLIC KEY-----";
        return openssl_get_publickey($publicKeyPem);
    }

    // Get private key from Base64 string
    private static function getPrivateKey($privateKey)
    {
        $privateKeyPem = "-----BEGIN PRIVATE KEY-----\n" . $privateKey . "\n-----END PRIVATE KEY-----";
        return openssl_get_privatekey($privateKeyPem);
    }

    // URL-safe Base64 encoding
    public static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // URL-safe Base64 decoding
    public static function base64UrlDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    // Read a PEM file
    public static function getKeyPem($file)
    {
        $pem = file_get_contents(Yii::getAlias($file));
        // Strip out the BEGIN/END tags
        $pem = preg_replace("/-----BEGIN (.*)-----/", "", $pem);
        $pem = preg_replace("/-----END (.*)-----/", "", $pem);
        return trim($pem);
    }
}
