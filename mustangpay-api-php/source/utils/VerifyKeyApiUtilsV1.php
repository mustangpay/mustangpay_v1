<?php

namespace app\utils;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class VerifyKeyApiUtilsV1 extends BaseKeyApiUtils
{
    private $client;
    private $logger;

    public function __construct()
    {
        $this->client = new Client();
        $this->logger = new Logger('mustangpay');
        $this->logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
    }

    /**
     * mustangPay创建加签报文
     *
     * @param string $mustangPayPrivateKey
     * @param string $merchantPublicKey
     */
    public function mustangPayCreateVerifyMessage($mustangPayPrivateKey, $merchantPublicKey)
    {
        try {
            $oneOrder = $this->createOrder();
            $srcBody = json_encode($oneOrder);
            $this->logger->info("request->srcBody: {$srcBody}");

            // 发送加签请求
            $body = $this->encryptToObject($srcBody, $mustangPayPrivateKey, $merchantPublicKey);

            // 将加密后的数据记录到文件
            $filePath = $this->getMustangPayFilePath();
            $this->writeToFile($filePath, $body);

            $this->logger->info("Data written to file: {$filePath}");

        } catch (\Exception $e) {
            $this->logger->error("mustangPay create verify message error", ['exception' => $e]);
        }
    }

    /**
     * 商户验证mustangPay提供的报文是否能够验签并解密成功
     *
     * @param string $merchantPrivateKey
     * @param string $mustangPayPublicKey
     */
    public function merchantCreateVerifyMessage($merchantPrivateKey, $mustangPayPublicKey)
    {
        try {
            $oneOrder = $this->createOrder();
            $srcBody = json_encode($oneOrder);
            $this->logger->info("request->srcBody: {$srcBody}");

            // 发送加签请求
            $body = $this->encryptToObject($srcBody, $merchantPrivateKey, $mustangPayPublicKey);

            // 将加密后的数据记录到文件
            $filePath = $this->getMustangPayFilePath();
            $this->writeToFile($filePath, $body);

            $this->logger->info("Data written to file: {$filePath}");

        } catch (\Exception $e) {
            $this->logger->error("mustangPay create verify message error", ['exception' => $e]);
        }
    }

    /**
     * 商户验证mustangPay给过来的带签名的报文
     *
     * @param string $merchantPrivateKey
     * @param string $mustangPayPublicKey
     */
    public function merchantVerifyMustangMessage($merchantPrivateKey, $mustangPayPublicKey)
    {
        try {
            $filePath = $this->getMustangPayFilePath();
            $content = $this->readFromFile($filePath);

            // 解密并验证
            $body = json_decode($content, true);
            $this->logger->info("body: " . json_encode($body));

            $srcBody = $this->decrypt($merchantPrivateKey, $mustangPayPublicKey, $body);
            $this->logger->info("srcBody: {$srcBody}");

        } catch (\Exception $e) {
            $this->logger->error("mustangPay verify message error", ['exception' => $e]);
        }
    }

    /**
     * mustangPay验证商户给过来的带签名的报文
     *
     * @param string $mustangPayPrivateKey
     * @param string $merchantPublicKey
     */
    public function mustangPayVerifyMustangMessage($mustangPayPrivateKey, $merchantPublicKey)
    {
        try {
            $filePath = $this->getMerchantFilePath();
            $content = $this->readFromFile($filePath);

            // 解密并验证
            $body = json_decode($content, true);
            $this->logger->info("body: " . json_encode($body));

            $srcBody = $this->decrypt($mustangPayPrivateKey, $merchantPublicKey, $body);
            $this->logger->info("srcBody: {$srcBody}");

        } catch (\Exception $e) {
            $this->logger->error("mustangPay verify message error", ['exception' => $e]);
        }
    }

}
