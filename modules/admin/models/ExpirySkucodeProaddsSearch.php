<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpirySkucodeProadds;

/**
 * ExpirySkucodeProaddsSearch represents the model behind the search form about `app\models\ExpirySkucodeProadds`.
 */
class ExpirySkucodeProaddsSearch extends ExpirySkucodeProadds
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'skucode', 'act_id', 'prize_id', 'num', 'total', 'status', 'batch'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ExpirySkucodeProadds::find()->orderBy(['id' => SORT_DESC]);;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'skucode' => $this->skucode,
            'act_id' => $this->act_id,
            'prize_id' => $this->prize_id,
            'num' => $this->num,
            'total' => $this->total,
            'status' => $this->status,
            'batch' => $this->batch,
        ]);

        return $dataProvider;
    }
}
