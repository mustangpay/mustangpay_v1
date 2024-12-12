<?php

namespace app\components;

use app\helpers\Helper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class BackUp
{
    public $path;
    public $_path = '/backup/';
    private static $instance;

    private function setPath()
    {
        $path = Yii::getAlias('@runtime' . rtrim($this->_path, '/\\') . '/');
        if (!file_exists($path)) {
            FileHelper::createDirectory($path);
        }
        $this->path = $path;
    }

    private function __construct()
    {
        $this->setPath();
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function list()
    {
        $data = Helper::fileData($this->path, '*.sql');
        rsort($data);
        return ArrayHelper::index($data, 'filename');
    }

    public function create(array $tables = [])
    {
        $backupName = $this->path . 'db_' . Yii::$app->security->generateRandomString(5) . '_' . date('Ymd-His') . '.sql';
        try {
            //没有指定 备份全部
            if (empty($tables)) {
                $sql = 'SHOW TABLES';
                $cmd = Yii::$app->db->createCommand($sql);
                $tables = $cmd->queryColumn();
            }

            $fp = fopen($backupName, 'w+');
            fwrite($fp, '-- -------------------------------------------' . PHP_EOL);
            fwrite($fp, 'SET AUTOCOMMIT = 0;' . PHP_EOL);
            fwrite($fp, 'START TRANSACTION;' . PHP_EOL);
            fwrite($fp, 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL);
            fwrite($fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL);
            fwrite($fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL);
            fwrite($fp, '-- -------------------------------------------' . PHP_EOL);
            fwrite($fp, '-- START BACKUP' . PHP_EOL);
            foreach ($tables as $table) {

                $sql = 'SHOW CREATE TABLE ' . $table;
                $cmd = Yii::$app->db->createCommand($sql);
                $tableRow = $cmd->queryOne();

                $tableStr = $tableRow['Create Table'] . ';';

                $tableStr = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $tableStr);
                $tableStr = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $tableStr);

                fwrite($fp, '-- TABLE `' . addslashes($table) . '`' . PHP_EOL);
                fwrite($fp, 'DROP TABLE IF EXISTS `' . addslashes($table) . '`;' . PHP_EOL);
                fwrite($fp, $tableStr . PHP_EOL . PHP_EOL);

                $sql = 'SELECT * FROM ' . $table;
                $cmd = Yii::$app->db->createCommand($sql);
                $dataReader = $cmd->query();

                fwrite($fp, '-- TABLE DATA ' . $table . PHP_EOL);
                foreach ($dataReader as $data) {
                    $itemNames = array_keys($data);
                    $itemNames = array_map("addslashes", $itemNames);
                    $items = join('`,`', $itemNames);
                    array_walk($data, function (&$v) {
                        if ($v === null) {
                            $v = 'null';
                        } elseif (is_string($v)) {
                            $v = "'" . addslashes($v) . "'";
                        } else {

                        }
                    });

                    $valueString = "(" . implode(',', $data) . ')';

                    $dataString = "INSERT INTO `$table` (`$items`) VALUES $valueString;" . PHP_EOL;
                    fwrite($fp, $dataString);
                }
            }

            fwrite($fp, '-- -------------------------------------------' . PHP_EOL);
            fwrite($fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL);
            fwrite($fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL);
            fwrite($fp, '-- -------------------------------------------' . PHP_EOL);
            fwrite($fp, '-- END BACKUP' . PHP_EOL);
            fclose($fp);
            unset($fp);
            return true;
        } catch (\Exception $e) {
            Yii::error('数据库备份失败' . $e->getMessage());
            return false;
        }
    }

    public function view($id)
    {
        return $this->path . $id;
    }

    public function delete($id)
    {
        try {
            if (unlink($this->path . $id)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Yii::error('数据库备份删除失败' . $e->getMessage());
            return false;
        }
    }

    public function restore($id)
    {
        try {
            $sql = file_get_contents($this->path . $id);
            $cmd = Yii::$app->db->createCommand($sql);
            $cmd->execute();
            return true;
        } catch (\Exception $e) {
            Yii::error('数据库还原失败' . $e->getMessage());
            return false;
        }
    }
}