<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpiryUserJoin;

/**
 * ExpiryUserJoinSearch represents the model behind the search form about `app\models\ExpiryUserJoin`.
 */
class ExpiryUserJoinSearch extends ExpiryUserJoin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'win_id', 'act_id', 'ctime', 'prize_id', 'user_id', 'batch', 'dwstatus'], 'integer'],
            [['prize_title', 'ticket_code', 'open_id', 'nickname', 'remark', 'onlineip'], 'safe'],
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
        $query = ExpiryUserJoin::find()->orderBy(['ctime' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'win_id' => $this->win_id,
            'act_id' => $this->act_id,
            'ctime' => $this->ctime,
            'prize_id' => $this->prize_id,
            'user_id' => $this->user_id,
            'batch' => $this->batch,
            'dwstatus' => $this->dwstatus,
        ]);

        $query->andFilterWhere(['like', 'prize_title', $this->prize_title])
            ->andFilterWhere(['like', 'ticket_code', $this->ticket_code])
            ->andFilterWhere(['like', 'open_id', $this->open_id])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'onlineip', $this->onlineip]);

        return $dataProvider;
    }
}
