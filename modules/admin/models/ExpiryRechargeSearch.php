<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpiryRecharge;

/**
 * ExpiryRechargeSearch represents the model behind the search form about `app\models\ExpiryRecharge`.
 */
class ExpiryRechargeSearch extends ExpiryRecharge
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'act_id', 'Recharge_type', 'status', 'add_time', 'user_id', 'recharge_name'], 'integer'],
            [['open_id', 'qq', 'mobile', 'orders_id', 'ticket_code', 'nickname', 'remark', 'onlineip'], 'safe'],
            [['recharge_money'], 'number'],
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
        $query = ExpiryRecharge::find();
        $query->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'act_id' => $this->act_id,
            'recharge_money' => $this->recharge_money,
            'Recharge_type' => $this->Recharge_type,
            'status' => $this->status,
            'add_time' => $this->add_time,
            'user_id' => $this->user_id,
            'recharge_name' => $this->recharge_name,
        ]);

        $query->andFilterWhere(['like', 'open_id', $this->open_id])
            ->andFilterWhere(['like', 'qq', $this->qq])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'orders_id', $this->orders_id])
            ->andFilterWhere(['like', 'ticket_code', $this->ticket_code])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'onlineip', $this->onlineip]);

        return $dataProvider;
    }
}
