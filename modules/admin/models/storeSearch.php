<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\store;

/**
 * storeSearch represents the model behind the search form about `app\models\store`.
 */
class storeSearch extends store
{
    public $dataProvider;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_num', 'memberid', 'store_name', 'store_addr', 'store_tel', 'contact', 'username', 'password', 'url','cid', 'major', 'minor','uuid', 'iBeacon_num', 'is_del'], 'safe'],
            [['creation_date'], 'integer'],
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
        $query = store::find()
            ->joinWith('gots')
            ->select("store.*, sum(case `code`.is_get WHEN 'Y' THEN 1 ELSE 0 END) as getNum, sum(case `code`.is_act WHEN 'Y' THEN 1 ELSE 0 END) as actNum, sum(case `code`.is_cost WHEN 'Y' THEN 1 ELSE 0 END) as costNum")
            ->groupBy('store.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);
        /////////////////////////////////////
        $dataSort = $dataProvider->getSort();
        $dataSort->attributes = array_merge($dataSort->attributes, [
            'getNum' => [
                'asc' => ['getNum' => SORT_ASC],
                'desc' => ['getNum' => SORT_DESC],
                'label' => 'getNum'
            ],
            'actNum' => [
                'asc' => ['actNum' => SORT_ASC],
                'desc' => ['actNum' => SORT_DESC],
                'label' => 'actNum'
            ],
            'costNum' => [
                'asc' => ['costNum' => SORT_ASC],
                'desc' => ['costNum' => SORT_DESC],
                'label' => 'costNum'
            ],
        ]);
        $dataProvider->setSort($dataSort);

        /////////////////////////////////////

        $query->andFilterWhere([
            'creation_date' => $this->creation_date,
        ]);

        $query->andFilterWhere(['like', 'store.id', $this->id])
            ->andFilterWhere(['like', 'store.store_num', $this->store_num])
            ->andFilterWhere(['like', 'store_name', $this->store_name])
            ->andFilterWhere(['like', 'store_addr', $this->store_addr])
            ->andFilterWhere(['like', 'store_tel', $this->store_tel])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'cid', $this->cid])
            ->andFilterWhere(['like', 'major', $this->major])
            ->andFilterWhere(['like', 'minor', $this->minor])
            ->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'memberid', $this->memberid])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'iBeacon_num', $this->iBeacon_num])
            ->andFilterWhere(['like', 'store.is_del', array_search($this->is_del, $this->attributeTypes()['is_del'])]);
        return $dataProvider;
    }


}
