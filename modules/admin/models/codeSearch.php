<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Code;

/**
 * codeSearch represents the model behind the search form about `app\models\Code`.
 */
class codeSearch extends Code
{
    public $start;
    public $end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'code', 'openid', 'gopenid', 'is_act', 'is_add_card', 'is_cost', 'is_get', 'headimgurl', 'nickname', 'from', 'user_tel', 'q1_job', 'q2_hotel', 'integral', 'is_del', 'store_num'], 'safe'],
            [['act_date', 'cost_date', 'creation_date'], 'integer'],
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
        $query = Code::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        $this->start = $params['codeSearch']['start'];
        $this->end = $params['codeSearch']['end'];
        if($this->start > $this->end){
            echo "<script>alert('开始时间不能大于结束时间')</script>";
            $this->end = '';
        }

        $this->load($params);

        $query->andFilterWhere([
            'act_date' => $this->act_date,
            'cost_date' => $this->cost_date,
            'creation_date' => $this->creation_date,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'store_num', $this->store_num])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'gopenid', $this->gopenid])
            ->andFilterWhere(['like', 'is_act', array_search($this->is_act, $this->attributeTypes()['is_act'])])
            ->andFilterWhere(['like', 'is_cost', array_search($this->is_cost, $this->attributeTypes()['is_cost'])])
            ->andFilterWhere(['like', 'is_get', array_search($this->is_get, $this->attributeTypes()['is_get'])])
            ->andFilterWhere(['like', 'is_add_card', array_search($this->is_add_card, $this->attributeTypes()['is_add_card'])])
            ->andFilterWhere(['like', 'headimgurl', $this->headimgurl])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'user_tel', $this->user_tel])
            ->andFilterWhere(['like', 'q1_job', $this->q1_job])
            ->andFilterWhere(['like', 'q2_hotel', $this->q2_hotel])
            ->andFilterWhere(['like', 'is_del', array_search($this->is_del, $this->attributeTypes()['is_del'])])
            ->andFilterWhere(['like', 'is_trans', array_search($this->is_trans, $this->attributeTypes()['is_trans'])])
            ->andFilterWhere(['like', 'from', array_search($this->from, $this->attributeTypes()['from'])]);
        if(!empty($this->start) && !empty($this->end)){
            $query->andFilterWhere(['between', 'get_date', strtotime($params['codeSearch']['start']), strtotime($params['codeSearch']['end']) + 3600*24]);
        }

        return $dataProvider;
    }
}
