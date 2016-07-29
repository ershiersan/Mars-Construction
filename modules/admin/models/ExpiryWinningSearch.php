<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpiryWinning;

/**
 * ExpiryWinningSearch represents the model behind the search form about `app\models\ExpiryWinning`.
 */
class ExpiryWinningSearch extends ExpiryWinning
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'podium_id', 'user_id', 'winning_type', 'act_id', 'is_winning', 'prize_id', 'status', 'add_time', 'podium_time', 'dwstatus', 'batch'], 'integer'],
            [['open_id', 'nickname', 'ticket_code', 'skucode', 'prize_name', 'prize_title', 'onlineip'], 'safe'],
            [['winning_money'], 'number'],
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
        $query = ExpiryWinning::find()->orderBy(['add_time' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'podium_id' => $this->podium_id,
            'user_id' => $this->user_id,
            'winning_type' => $this->winning_type,
            'winning_money' => $this->winning_money,
            'act_id' => $this->act_id,
            'is_winning' => $this->is_winning,
            'prize_id' => $this->prize_id,
            'status' => $this->status,
            'add_time' => $this->add_time,
            'podium_time' => $this->podium_time,
            'dwstatus' => $this->dwstatus,
            'batch' => $this->batch,
        ]);

        $query->andFilterWhere(['like', 'open_id', $this->open_id])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'ticket_code', $this->ticket_code])
            ->andFilterWhere(['like', 'skucode', $this->skucode])
            ->andFilterWhere(['like', 'prize_name', $this->prize_name])
            ->andFilterWhere(['like', 'prize_title', $this->prize_title])
            ->andFilterWhere(['like', 'onlineip', $this->onlineip]);

        return $dataProvider;
    }
}
