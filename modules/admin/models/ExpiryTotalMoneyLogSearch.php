<?php

namespace app\modules\admin\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpiryTotalMoneyLog;

/**
 * ExpiryTotalMoneyLogSearch represents the model behind the search form about `app\models\ExpiryTotalMoneyLog`.
 */
class ExpiryTotalMoneyLogSearch extends ExpiryTotalMoneyLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'total_money_time'], 'integer'],
            [['theday','theday_time', 'set_total_money', 'real_total_money'], 'safe'],
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
        $query = ExpiryTotalMoneyLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'total_money_time' => $this->total_money_time,
        ]);
        if ( !empty($this->theday) && !strtotime($this->theday)){
            $this->theday = date('Y-m-d', $this->theday);
        }    
        
        $query->andFilterWhere(['like', 'theday', $this->theday])
            ->andFilterWhere(['like', 'set_total_money', $this->set_total_money])
            ->andFilterWhere(['like', 'real_total_money', $this->real_total_money]);

        return $dataProvider;
    }
}
