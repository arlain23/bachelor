<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FileCategory;

/**
 * FileCategorySearch represents the model behind the search form about `backend\models\FileCategory`.
 */
class FileCategorySearch extends FileCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryID'], 'integer'],
            [['categoryName'], 'safe'],
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
        $query = FileCategory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'categoryID' => $this->categoryID,
        ]);

        $query->andFilterWhere(['like', 'categoryName', $this->categoryName]);

        return $dataProvider;
    }
}
