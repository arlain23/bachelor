<?php

namespace backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FileEntry;

/**
 * FileEntrySearcher represents the model behind the search form about `\common\models\FileEntry`.
 */
class FileEntrySearcher extends FileEntry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fileEntryId'], 'integer'],
            [['title', 'createDate', 'patient', 'fileURL', 'gifURL', 'fileExtension', 'description', 'content'], 'safe'],
            [['fileSize'], 'number'],
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
        $query = FileEntry::find();

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
            'fileEntryId' => $this->fileEntryId,
            'createDate' => $this->createDate,
            'fileSize' => $this->fileSize,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'patient', $this->patient])
            ->andFilterWhere(['like', 'fileURL', $this->fileURL])
            ->andFilterWhere(['like', 'gifURL', $this->gifURL])
            ->andFilterWhere(['like', 'fileExtension', $this->fileExtension])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
