<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductLotteryUser;

/**
 * ProductLotteryUserSearch represents the model behind the search form about `app\models\ProductLotteryUser`.
 */
class ProductLotteryUserSearch extends ProductLotteryUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'balance', 'total_balance', 'join_num', 'winnum', 'total_num', 'update_date', 'create_date'], 'integer'],
            [['mobile'], 'number'],
            [['unionid', 'open_id', 'nickname', 'qs_id', 'mobile', 'is_del', 'total_balance_start', 'total_balance_end', 'join_num_start', 'join_num_end', 'winnum_start', 'winnum_end', 'update_date_start', 'update_date_end', 'province', 'city', 'province_name', 'city_name'], 'safe'],
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
        $query = ProductLotteryUser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'balance' => $this->balance,
            'open_id' => $this->open_id,
            'total_balance' => $this->total_balance,
            'join_num' => $this->join_num,
            'winnum' => $this->winnum,
            'total_num' => $this->total_num,
            'update_date' => $this->update_date,
            'create_date' => $this->create_date,
        ]);

        $query->andFilterWhere(['like', 'unionid', $this->unionid])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'qs_id', $this->qs_id])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'province', $this->province_name])
            ->andFilterWhere(['like', 'city', $this->city_name])
            ->andFilterWhere(['like', 'is_del', 'N']);

        if($this->total_balance_start) {
            $query->andFilterWhere(['>=', 'total_balance', $this->total_balance_start]);
        }
        if($this->total_balance_end) {
            $query->andFilterWhere(['<=', 'total_balance', $this->total_balance_end]);
        }

        if($this->join_num_start) {
            $query->andFilterWhere(['>=', 'join_num', $this->join_num_start]);
        }
        if($this->join_num_end) {
            $query->andFilterWhere(['<=', 'join_num', $this->join_num_end]);
        }

        if($this->winnum_start) {
            $query->andFilterWhere(['>=', 'winnum', $this->winnum_start]);
        }
        if($this->winnum_end) {
            $query->andFilterWhere(['<=', 'winnum', $this->winnum_end]);
        }

        if($this->update_date_start) {
            $query->andFilterWhere(['>', 'update_date', strtotime($this->update_date_start)]);
        }
        if($this->update_date_end) {
            $query->andFilterWhere(['<', 'update_date', strtotime($this->update_date_end) + 3600 * 24]);
        }

        // die($query->createCommand()->getRawSql());
        return $dataProvider;
    }
}
