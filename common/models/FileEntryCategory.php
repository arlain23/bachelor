<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fileentrycategory".
 *
 * @property integer $fileEntryCategoryID
 * @property integer $fileEntryID
 * @property integer $categoryID
 */
class FileEntryCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fileentrycategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fileEntryID', 'categoryID'], 'required'],
            [['fileEntryID', 'categoryID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fileEntryCategoryID' => 'File Entry Category ID',
            'fileEntryID' => 'File Entry ID',
            'categoryID' => 'Category ID',
        ];
    }
}