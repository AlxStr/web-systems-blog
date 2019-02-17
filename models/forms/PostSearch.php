<?php

namespace app\models\forms;

use app\models\Post;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class PostSearch extends Model
{
    public $id;
    public $title;
    public $category_id;
    public $logo;
    public $body;
    public $status;
    public $date_create;
    public $date_update;

    public function rules()
    {
        return [
            [['id', 'category_id'], 'integer'],
            [['title', 'status'], 'safe'],
            [['date_create', 'date_update'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function search($params, $pagination = 20)
    {
        $query = Post::find()->with('category', 'postAuthor');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pagination,
            ],
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ]
            ],
        ]);

        if(isset($params['only_active']) && $params['only_active'])
        {
            $dataProvider->query->andFilterWhere(['status'=> Post::ACTIVE]);
        }

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['>=', 'created_at', $this->date_create ? strtotime($this->date_create . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_create ? strtotime($this->date_create . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'updated_at', $this->date_update ? strtotime($this->date_update . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'updated_at', $this->date_update ? strtotime($this->date_update . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
