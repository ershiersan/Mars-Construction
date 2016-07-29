<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SkucodeIndex;

/**
 * SkucodeIndexSearch represents the model behind the search form about `app\models\SkucodeIndex`.
 */
class SkucodeIndexSearch extends SkucodeIndex
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'batch', 'waveid', 'total_num', 'begin_time', 'expiry_time', 'create_date'], 'integer'],
            [['skucode', 'epinscode', 'names', 'total_pro', 'is_del'], 'safe'],
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
        $query = SkucodeIndex::find()->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'batch' => $this->batch,
            'waveid' => $this->waveid,
            'total_num' => $this->total_num,
            'begin_time' => $this->begin_time,
            'expiry_time' => $this->expiry_time,
            'create_date' => $this->create_date,
        ]);

        $query->andFilterWhere(['like', 'skucode', $this->skucode])
            ->andFilterWhere(['like', 'epinscode', $this->epinscode])
            ->andFilterWhere(['like', 'names', $this->names])
            ->andFilterWhere(['like', 'total_pro', $this->total_pro])
            ->andFilterWhere(['like', 'is_del', 'N']);

        return $dataProvider;
    }
}
