<?php

namespace app\helpers;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;

class Helper
{
    /**
     * 截取字符串
     *
     * @param string $string
     * @param int $length
     * @param string $dot
     * @return mixed|string
     */
    public static function cutStr($string, $length, $dot = ' ...')
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

        $strCut = '';

        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {

            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }

            if ($noc >= $length) {
                break;
            }

        }
        if ($noc > $length) {
            $n -= $tn;
        }

        $strCut = substr($string, 0, $n);

        $strCut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strCut);

        $pos = strrpos($strCut, chr(1));
        if ($pos !== false) {
            $strCut = substr($strCut, 0, $pos);
        }
        return $strCut . ($strCut != $string ? $dot : '');
    }

    /**
     * 添加指定后缀
     *
     * @param $filename
     * @param $suffix
     * @return string
     */
    public static function addSuffix($filename, $suffix)
    {
        $fps = pathinfo($filename);
        $filename = $fps['filename'] . $suffix . '.' . self::arrayValue($fps, 'extension', '');
        return $fps['dirname'] . '/' . $filename;
    }

    /**
     * 生成随机字符
     *
     * @param $length
     * @param $numeric
     * @return string
     */
    public static function random($length, $numeric = false)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 是否为本地文件
     *
     * @param $file
     * @return bool
     */
    public static function isLocalFile($file)
    {
        $valueParse = parse_url($file);
        return !isset($valueParse['host']);
    }

    /**
     * 删除文件
     *
     * @param $file
     * @return void
     */
    public static function unlink($file)
    {
        if (self::isLocalFile($file)) {
            $path = $file;
            $path = Yii::getAlias('@webroot') . str_replace('/', DIRECTORY_SEPARATOR, $path);
            @unlink($path);
        }
    }

    /**
     * 隐藏指定字符串
     *
     * @param $text
     * @param $left
     * @param $right
     * @return mixed|string
     */
    public static function hiddenMiddle($text, $left = 3, $right = 3)
    {
        $length = mb_strlen($text);
        if ($length <= $left + $right) {
            $left = 1;
            $right = 1;
        }
        if ($length > $left + $right) {
            return mb_substr($text, 0, $left) . str_repeat('*', $length - $left - $right) . ($right ? mb_substr($text, -1 * $right) : '');
        }
        return $text;
    }

    /**
     * 隐藏指定中文字符串
     *
     * @param $name
     * @return string
     */
    public static function hiddenChineseName($name)
    {
        $name = trim($name);
        if (empty($name)) {
            return '';
        }

        $length = mb_strlen($name);
        if ($length > 1) {
            return mb_substr($name, 0, 1) . str_repeat('*', $length - 1);
        } else {
            return $name;
        }
    }

    /**
     * 模型上传文件
     *
     * @param $model yii\base\Model
     * @param $file yii\web\UploadedFile
     * @param $field string
     * @param $dir string
     * @param null $imageSize string
     * @throws yii\base\Exception
     */
    public static function modelUpload($model, $file, $field, $dir, $imageSize = null)
    {
        if (!$file) {
            return;
        }

        $path = Yii::$app->params['uploadPath'] . $dir . '/';
        $fullPath = Yii::getAlias('@webroot' . $path);
        if (!file_exists($fullPath)) {
            FileHelper::createDirectory($fullPath);
        }
        $filename = date('ymdHis') . mt_rand(10000, 99999) . '.' . $file->extension;
        if ($file->saveAs($fullPath . $filename)) {
            self::imgCrop($fullPath . $filename, $imageSize);
            if ($model->$field) {
                @unlink(Yii::getAlias('@webroot') . $model->$field);
            }
            $model->$field = $path . $filename;
        } else {
            Yii::error($file->error);
        }
    }

    /**
     * 处理图片大小的格式
     *
     * @param $size
     * @return array|null
     */
    public static function sizeArr($size)
    {
        try {
            list($w, $h) = explode('*', $size);
        } catch (\Exception $e) {
            return null;
        }
        $w = intval($w);
        $h = intval($h);
        if ($w < 10 || $w > 3000 || $h < 10 || $h > 3000) {
            return null;
        }

        return ['width' => $w, 'height' => $h];
    }

    /**
     * 裁切图片
     *
     * @param $file
     * @param $size
     * @return void
     */
    public static function imgCrop($file, $size)
    {
        if (!file_exists($file)) {
            return;
        }
        $sizeArr = self::sizeArr($size);
        if ($sizeArr) {
            \yii\imagine\Image::thumbnail($file, $sizeArr['width'], $sizeArr['height'])->save($file);
        }
    }

    public static function arrayValue($array, $key, $defValue = false)
    {
        return isset($array[$key]) ? $array[$key] : $defValue;
    }

    /**
     * 获取指定文件下指定扩展文件
     *
     * @param $path
     * @param $ext
     * @return array
     */
    public static function fileData($path, $ext = '*.*')
    {
        $path = Yii::getAlias(rtrim($path, '/\\') . '/');

        $result = [];
        foreach (glob($path . $ext) as $filename) {
            $result[] = [
                'filename' => basename($filename),
                'fullName' => $filename,
                'size' => @filesize($filename),
                'fileTime' => @filemtime($filename),
            ];
        }

        return $result;
    }

    //判断 是否 是 微信浏览器
    public static function isWeChatBrowser()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断是否为手机
     *
     * @return bool
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为false,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientKeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientKeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 将xml转为array
     *
     * @param string $xml
     * @return array|mixed
     */
    public static function fromXml($xml)
    {
        if (!$xml) {
            return [];
        }
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 输出xml字符
     *
     * @param $param
     * @return string
     */
    public static function toXml($param)
    {
        if (!is_array($param) || count($param) <= 0) {
            return '';
        }

        $xml = "<xml>";
        foreach ($param as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 文本高亮
     *
     * @param $str
     * @param $words
     * @param string $color
     * @return string
     * 文本高亮
     */
    public static function highText($str, $words, $color = '#ff1c1c')
    {
        $wordArr = explode(" ", $words);
        foreach ($wordArr as $word) {
            $str = strtr($str, "|($word)|Ui", "<span style='color: $color'><b>$word</b></span>");
        }
        return $str;
    }

    /**
     * 求两个两个经纬度之间的距离
     *
     * @param string $latitude1
     * @param string $longitude1
     * @param string $latitude2
     * @param $longitude2
     * @return string $longitude2
     */
    public static function twoPointDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return (string)compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }

    public static function generateSku(array $data)
    {
        if (count($data) >= 2) {
            $tmpArr = [];
            $arr1 = array_shift($data);
            $arr2 = array_shift($data);
            foreach ($arr1 as $v1) {
                foreach ($arr2 as $v2) {
                    $tmpArr[] = array_merge((array)$v1, array($v2));
                }
            }
            array_unshift($data, $tmpArr);
            $data = self::generateSku($data);
        }
        return $data;
    }

    /**
     * 格式化文件路径
     *
     * @param $url
     * @return mixed|string
     */
    public static function getFullUrl($url)
    {
        return $url ? (stripos($url, 'http') !== false ? $url : Url::to('@web' . $url, true)) : '';
    }

    /**
     * 删除图片
     *
     * @param $id
     * @param $field
     * @param $model
     * @return bool
     */
    public static function ImageDelete($id, $field, $model)
    {
        $model = $model::findOne($id);
        if ($model->$field) {
            self::unlink($model->$field);
            $model->updateAttributes([$field => '']);
            return true;
        }
        return false;
    }

    /**
     * 获取数据库大小
     *
     * @return int
     * @throws \yii\db\Exception
     */
    public static function getDefaultDbSize()
    {
        $db = Yii::$app->db;
        $models = $db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);
        // 数据库大小
        $mysqlSize = 0;
        foreach ($models as $model) {
            $mysqlSize += $model['data_length'];
        }

        return $mysqlSize;
    }

    public static function getSupportExtendHtml($extend)
    {
        return extension_loaded($extend) ? '<span class="label label-primary">' . $extend . '支持</span>' : '<span class="label label-default">' . $extend . '不支持</span>';
    }


    /**
     * 解析错误
     *
     * @param $firstErrors
     * @return string
     */
    public static function analysisErr($firstErrors)
    {
        if (!is_array($firstErrors) || empty($firstErrors)) {
            return false;
        }

        $errors = array_values($firstErrors)[0];
        return $errors ?? Yii::t('app', '未捕获到错误信息');
    }

    /**
     * 驼峰法命名 转 中横线
     * @param $name
     * @return string
     */
    public static function transName($name)
    {
        return trim(preg_replace('/\p{Lu}/u', '-\0', $name), '-');
    }
}
