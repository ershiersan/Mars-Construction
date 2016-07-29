<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WxMassMaterialNews;

/**
 * WxMassMaterialNews represents the model behind the search form about `app\models\WxMassMaterialNews`.
 */
class WxMassMaterialNewsSearch extends WxMassMaterialNews
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'wx_system_user_id', 'material_id', 'show_cover_pic', 'graphic_type', 'create_at'], 'integer'],
            [['thumb_media_id', 'local_media_id', 'author', 'title', 'content_source_url', 'wx_url', 'invest_link', 'appkey_id', 'content', 'digest', 'app_info', 'app_sname', 'app_name', 'is_act_del', 'is_del', 'label'], 'safe'],
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
        $query = WxMassMaterialNews::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $query->andWhere(['<>', 'appkey_id', '']);

        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'wx_system_user_id' => 4,
            'material_id' => $this->material_id,
            'show_cover_pic' => $this->show_cover_pic,
            'graphic_type' => $this->graphic_type,
            'create_at' => $this->create_at,
        ]);

        $query->andFilterWhere(['like', 'thumb_media_id', $this->thumb_media_id])
            ->andFilterWhere(['like', 'local_media_id', $this->local_media_id])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content_source_url', $this->content_source_url])
            ->andFilterWhere(['like', 'wx_url', $this->wx_url])
            ->andFilterWhere(['like', 'invest_link', $this->invest_link])
            ->andFilterWhere(['like', 'appkey_id', $this->appkey_id])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'digest', $this->digest])
            ->andFilterWhere(['like', 'app_info', $this->app_info])
            ->andFilterWhere(['like', 'app_sname', $this->app_sname])
            ->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'is_act_del', $this->is_act_del])
            ->andFilterWhere(['like', 'is_del', 'N'])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
