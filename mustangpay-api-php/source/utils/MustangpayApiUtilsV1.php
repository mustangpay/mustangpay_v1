<?php

namespace app\utils;

use app\constants\MustangpayApiConstantsV1;
use GuzzleHttp\Exception\RequestException;
use Yii;
use GuzzleHttp\Client;

class MustangpayApiUtilsV1
{
    private static $requestConfig = [
        'connect_timeout' => 15,
        'timeout' => 90,
    ];

    private static function filterData($data)
    {
        foreach ($data as $k => &$value) {
            if (is_null($value)) {
                unset($data[$k]);
            }
        }
        return $data;
    }

    // Call the MustangPay API (Test)
    public static function callTest($logPrefix, $data, $jumpKey)
    {
        try {
            $srcBody = json_encode($data);
            // Encrypt and sign the data
            $sendJson = json_encode(self::encryptToObject($srcBody, RSAUtils::getKeyPem(Yii::$app->params['certFile']['mustangPayPublicKeyPath']), MustangpayApiConstantsV1::MERCHANT_ID));
            error_log("{$logPrefix}|reqeust->sendJson: {$sendJson}");
            $client = new Client([
                'base_uri' => MustangpayApiConstantsV1::geTestMustangPayApiUrl($jumpKey),
                'timeout' => self::$requestConfig['timeout'],
                'connect_timeout' => self::$requestConfig['connect_timeout'],
            ]);

            $response = $client->post('', [
                'json' => json_decode($sendJson, true),
                'headers' => [
                    'Content-Type' => 'application/json;charset=UTF-8',
                    'merchantId' => 'merchantId',  // You can replace this with actual merchant ID
                ],
            ]);
            $responseStr = $response->getBody()->getContents();
            error_log("{$logPrefix}|repsonse->str: {$responseStr}");
            $accessBody = json_decode($responseStr, true);
            $body = self::merchantDecrypt($accessBody);
            if ($body === null) {
                throw new \RuntimeException("收到的响应：验签失败");
            }
            error_log("{$logPrefix}|response->验签成功");

            return json_decode($body, true);

        } catch (RequestException $e) {
            error_log("Mustangpay接口失败: " . $e->getMessage());
            return null;
        }
    }

    // Decrypt response and verify signature
    private static function merchantDecrypt($body)
    {
        try {
            $toEncryptBodyStr = json_encode($body);
            $toEncryptBody = json_decode($toEncryptBodyStr, true);

            $encryptKey = $toEncryptBody['encryptKey'];
            $encryptData = $toEncryptBody['encryptData'];

            // Decrypt AES key using RSA
            $aesKey = RSAUtils::privateDecrypt($encryptKey, RSAUtils::getKeyPem(Yii::$app->params['certFile']['rsaPrivateKeyPath']));
            // Decrypt data using AES
            $originalData = AESUtil::decrypt($encryptData, $aesKey);
            $originalDataObj = json_decode($originalData, true);
            $sign = $originalDataObj['sign'] ?? null;
            if (empty($sign)) {
                error_log("RSA sign is empty");
                return null;
            }

            // Remove sign and verify
            unset($originalDataObj['sign']);
            ksort($originalDataObj);
            $originalDataObjNoSignStr = json_encode($originalDataObj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $isVerified = RSAUtils::verify($originalDataObjNoSignStr, RSAUtils::getKeyPem(Yii::$app->params['certFile']['mustangPayPublicKeyPath']), $sign);

            if ($isVerified) {
                return $originalData;
            } else {
                error_log("RSA sign verification failed");
            }

        } catch (\Exception $e) {
            error_log("RSA decryption error: " . $e->getMessage());
        }
        return null;
    }

    // Encrypt data to object
    private static function encryptToObject($response, $mustangPayPublicKey, $merchantId)
    {
        if (empty($mustangPayPublicKey)) {
            error_log("Public key error");
        }
        $jsonObject = json_decode($response, true);
        $jsonObject = self::filterData($jsonObject);
        ksort($jsonObject);
        // Sign with RSA private key
        $sign = RSAUtils::sign(json_encode($jsonObject, JSON_UNESCAPED_UNICODE), RSAUtils::getKeyPem(Yii::$app->params['certFile']['rsaPrivateKeyPath']));
        $jsonObject['sign'] = $sign;
        // Encrypt the data with AES
        $aesKey = bin2hex(random_bytes(16));  // Use a UUID as AES key (or random key generation)
        error_log("AES Key: {$aesKey}");

        $encryptData = AESUtil::encrypt(json_encode($jsonObject, JSON_UNESCAPED_UNICODE), $aesKey);

        // Encrypt AES key using RSA
        $encryptKey = RSAUtils::publicEncrypt($aesKey, $mustangPayPublicKey);
        return [
            'encryptKey' => $encryptKey,
            'encryptData' => $encryptData,
            'merchantId' => $merchantId
        ];
    }

}
