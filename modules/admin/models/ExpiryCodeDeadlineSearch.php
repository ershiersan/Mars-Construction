<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpiryCodeDeadline;

/**
 * ExpiryCodeDeadlineSearch represents the model behind the search form about `app\models\ExpiryCodeDeadline`.
 */
class ExpiryCodeDeadlineSearch extends ExpiryCodeDeadline
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'skucode', 'act_id', 'batch', 'sell_time', 'begin_time', 'expiry', 'create_time'], 'integer'],
            [['is_del'], 'safe'],
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
        $query = ExpiryCodeDeadline::find()->orderBy(['id' => SORT_DESC]);;

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
            'batch' => $this->batch,
            'sell_time' => $this->sell_time,
            'begin_time' => $this->begin_time,
            'expiry' => $this->expiry,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'is_del', 'N']);

        return $dataProvider;
    }
}
