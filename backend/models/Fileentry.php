<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fileentry".
 *
 * @property integer $fileEntryId
 * @property string $title
 * @property string $createDate
 * @property string $patient
 * @property string $fileURL
 * @property string $gifURL
 * @property string $fileExtension
 * @property double $fileSize
 * @property string $description
 * @property string $content
 *
 * @property Fileentrycategory[] $fileentrycategories
 */
class Fileentry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fileentry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'createDate', 'patient', 'fileURL', 'gifURL', 'fileExtension', 'fileSize', 'description', 'content'], 'required'],
            [['createDate'], 'safe'],
            [['fileURL', 'gifURL', 'description', 'content'], 'string'],
            [['fileSize'], 'number'],
            [['title', 'patient'], 'string', 'max' => 75],
            [['fileExtension'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fileEntryId' => 'File Entry ID',
            'title' => 'Title',
            'createDate' => 'Create Date',
            'patient' => 'Patient',
            'fileURL' => 'File Url',
            'gifURL' => 'Gif Url',
            'fileExtension' => 'File Extension',
            'fileSize' => 'File Size',
            'description' => 'Description',
            'content' => 'Content',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFileentrycategories()
    {
        return $this->hasMany(Fileentrycategory::className(), ['fileEntryID' => 'fileEntryId']);
    }
}
