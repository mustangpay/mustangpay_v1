<?php

namespace app\modules\admin\models\searchs;

use app\models\Param;
use yii\data\ActiveDataProvider;

class ParamSearch extends Param

{
    public function search($params)
    {
        $this->load($params);
        $query = self::find();

        $query->andFilterWhere([
            'cate_id' => $this->cate_id,
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
        ]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'cate_id' => SORT_ASC,
                    'order_by' => SORT_DESC,
                ]

            ]
        ]);
    }

}