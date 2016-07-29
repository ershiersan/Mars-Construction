<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpiryErrorLogs;

/**
 * ExpiryErrorLogsSearch represents the model behind the search form about `app\models\ExpiryErrorLogs`.
 */
class ExpiryErrorLogsSearch extends ExpiryErrorLogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'errorcode', 'create_time'], 'integer'],
            [['open_id', 'code', 'errormsg', 'onlineip', 'refer'], 'safe'],
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
        $query = ExpiryErrorLogs::find()->orderBy(['create_time' => SORT_DESC]);;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'errorcode' => $this->errorcode,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'open_id', $this->open_id])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'errormsg', $this->errormsg])
            ->andFilterWhere(['like', 'onlineip', $this->onlineip])
            ->andFilterWhere(['like', 'refer', $this->refer]);

        return $dataProvider;
    }
}
