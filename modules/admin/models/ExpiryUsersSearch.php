<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpiryUsers;

/**
 * ExpiryUsersSearch represents the model behind the search form about `app\models\ExpiryUsers`.
 */
class ExpiryUsersSearch extends ExpiryUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'msex_wx', 'add_time', 'joinnum', 'winnum', 'totalnum', 'totaldialnum', 'surplusnum', 'total_type', 'plusnum', 'bigwinnum'], 'integer'],
            [['open_id', 'pic_url', 'nickname', 'city'], 'safe'],
            [['balance', 'total_balance'], 'number'],
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
        $query = ExpiryUsers::find()->orderBy(['user_id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'msex_wx' => $this->msex_wx,
            'balance' => $this->balance,
            'total_balance' => $this->total_balance,
            'add_time' => $this->add_time,
            'joinnum' => $this->joinnum,
            'winnum' => $this->winnum,
            'totalnum' => $this->totalnum,
            'totaldialnum' => $this->totaldialnum,
            'surplusnum' => $this->surplusnum,
            'total_type' => $this->total_type,
            'plusnum' => $this->plusnum,
            'bigwinnum' => $this->bigwinnum,
        ]);

        $query->andFilterWhere(['like', 'open_id', $this->open_id])
            ->andFilterWhere(['like', 'pic_url', $this->pic_url])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'city', $this->city]);

        return $dataProvider;
    }
}
