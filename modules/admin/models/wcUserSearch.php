<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WcUser;

/**
 * wcUserSearch represents the model behind the search form about `app\models\WcUser`.
 */
class wcUserSearch extends WcUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid', 'gopenid', 'unionid', 'nickname', 'province', 'city', 'country', 'headimgurl', 'language', 'privilege', 'is_op', 'is_del'], 'safe'],
            [['sex', 'creation_date'], 'integer'],
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
        $query = WcUser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'sex' => $this->sex,
            'creation_date' => $this->creation_date,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'gopenid', $this->gopenid])
            ->andFilterWhere(['like', 'unionid', $this->unionid])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'headimgurl', $this->headimgurl])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'privilege', $this->privilege])
            ->andFilterWhere(['like', 'is_op', $this->is_op])
            ->andFilterWhere(['like', 'is_del', array_search($this->is_del, $this->attributeTypes()['is_del'])]);
        return $dataProvider;
    }
}
