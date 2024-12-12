<?php

namespace app\modules\admin\models\searchs;

use app\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public function search($params)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $this->load($params);
        $query->andFilterWhere(['like', 'nickname', $this->nickname]);
        $query->andFilterWhere(['like', 'mobile', $this->mobile]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['group' => $this->group, 'status' => $this->status]);

        return $dataProvider;
    }
}