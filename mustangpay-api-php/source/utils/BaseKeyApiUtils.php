<?php

namespace app\utils;

class BaseKeyApiUtils
{
    protected function createOrder()
    {
        // 假设这是创建订单的逻辑，可以根据你的需求调整
        return [
            'orderId' => '123456',
            'amount' => '100',
            'currency' => 'USD',
            'merchant' => 'merchant_name',
        ];
    }

    protected function encryptToObject($srcBody, $privateKey, $publicKey)
    {
        // 使用openssl加密的逻辑
        // 实际加解密方法依据私钥、公钥进行加密
        $encryptedData = openssl_encrypt($srcBody, 'RSA', $privateKey);
        return [
            'encryptedData' => $encryptedData,
            'publicKey' => $publicKey
        ];
    }

    protected function decrypt($privateKey, $publicKey, $body)
    {
        // 解密逻辑
        $encryptedData = $body['encryptedData'];
        openssl_decrypt($encryptedData, 'RSA', $privateKey);
        return 'decrypted data';
    }

    protected function getMustangPayFilePath()
    {
        // 文件路径的获取逻辑
        return '/path/to/mustangpay/file.json';
    }

    protected function getMerchantFilePath()
    {
        // 文件路径的获取逻辑
        return '/path/to/merchant/file.json';
    }

    protected function writeToFile($filePath, $data)
    {
        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    protected function readFromFile($filePath)
    {
        return file_get_contents($filePath);
    }
}
