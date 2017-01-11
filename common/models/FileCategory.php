<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "filecategory".
 *
 * @property integer $categoryID
 * @property string $categoryName
 */
class FileCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filecategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryName'], 'required'],
            [['categoryName'], 'string', 'max' => 75],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categoryID' => 'Category ID',
            'categoryName' => 'Category Name',
        ];
    }
}