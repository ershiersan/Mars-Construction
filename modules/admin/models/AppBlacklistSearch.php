<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AppBlacklist;

/**
 * AppBlacklistSearch represents the model behind the search form about `app\models\AppBlacklist`.
 */
class AppBlacklistSearch extends AppBlacklist
{

    public $start;
    public $end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'keytype', 'blocktype', 'frozen_num', 'frozen_time', 'unfrozen_time', 'create_time', 'update_time'], 'integer'],
            [['key', 'add_type', 'add_msg', 'is_del'], 'safe'],
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
        $query = AppBlacklist::find();
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
            'keytype' => $this->keytype,
            'blocktype' => $this->blocktype,
            'frozen_num' => $this->frozen_num,
            'frozen_time' => $this->frozen_time,
            'unfrozen_time' => $this->unfrozen_time,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'add_type', $this->add_type])
            ->andFilterWhere(['like', 'add_msg', $this->add_msg])
            ->andFilterWhere(['like', 'is_del', 'N']);

        if(isset($params['is_frozen'])) {
            if($params['is_frozen'] == 'N') {
                $query->andFilterWhere(['<', 'unfrozen_time', time()]);
            } else if($params['is_frozen'] == 'Y') {
                $query->andFilterWhere(['>', 'unfrozen_time', time()]);
            }
        }

        if(!empty($this->start) && !empty($this->end)){
            $query->andFilterWhere(['between', 'get_date', strtotime($params['codeSearch']['start']), strtotime($params['codeSearch']['end']) + 3600*24]);
        }
        return $dataProvider;
    }
}
