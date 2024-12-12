<?php
namespace app\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * 递归数组
     *
     * @param array $items
     * @param string $idField
     * @param int $pid
     * @param string $pidField
     * @return array
     */
    public static function itemsMerge(array $items, $pid = 0, $idField = "id", $pidField = 'pid', $child = 'children')
    {
        $map = [];
        $tree = [];
        foreach ($items as &$it) {
            $it[$child] = [];
            $map[$it[$idField]] = &$it;
        }

        foreach ($items as &$it) {
            $parent = &$map[$it[$pidField]];
            if ($parent) {
                $parent[$child][] = &$it;
            } else {
                $pid == $it[$pidField] && $tree[] = &$it;
            }
        }

        unset($items, $map);

        return $tree;
    }
    /**
     *
     * 根据 级别 和 数组 返回字符串
     * @param $level
     * @param array $models
     * @param $k
     * @param int $treeStat
     */
    public static  function itemsLevel($level , array $models , $k , $treeStat = 1){
        $str = '';
        for ($i = 1; $i < $level; $i++) {
            $str .= '　　';

            if ($i == $level - $treeStat) {
                if (isset($models[$k + 1])) {
                    return $str . "├──";
                }

                return $str . "└──";
            }
        }
        return '';
    }
    /**
     * 必须经过递归才能进行重组为下拉框
     *
     * @param $models
     * @param string $idField
     * @param string $titleField
     * @param int $treeStat
     * @return array
     */
    public static function itemsMergeDropDown($models, $idField = 'id', $titleField = 'title',  $treeStat = 1 , $child = 'children')
    {
        $arr = [];
        foreach ($models as $k => $model) {
            $arr[] = [
                $idField => $model[$idField],
                $titleField => self::itemsLevel($model['level'] , $models, $k, $treeStat) . " " . $model[$titleField],
            ];

            if (!empty($model[$child])) {
                $arr = ArrayHelper::merge($arr , self::itemsMergeDropDown($model['children'], $idField, $titleField, $treeStat , $child));
            }
        }
        return $arr;
    }

    /**
     * 传递一个子分类ID返回所有的父级分类
     *
     * @param array $items
     * @param $id
     * @return array
     */
    public static function getParents(array $items, $id)
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr = array_merge(self::getParents($items, $v['pid']), $arr);
            }
        }

        return $arr;
    }

    /**
     * 传递一个父级分类ID返回所有子分类
     *
     * @param array $items
     * @param int $pid
     * @return array
     */
    public static function getChildren(array $items, $pid)
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
                $arr = array_merge($arr, self::getChildren($items, $v['id']));
            }
        }

        return $arr;
    }
}