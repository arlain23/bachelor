<?php

namespace backend\models;

use Yii;

class FileForm extends \yii\base\Model
{
	public $title;
	public $description;
	public $patient;
	public $file;
	public $preview;
	public $extension;
	public $remoteUrl;
	public $remotePreviewUrl;
	public $content;
	public $categories;
	public $size;

	
	public function rules()
	{
		return [
				// define validation rules here
				[['title', 'description','patient','content','categories'], 'required'],
				[['file'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType'=>false, 'extensions' => 'nii, npy, vtk'],
				[['preview'], 'file','extensions' => 'png, jpg, bmp, gif'],
				[['title','patient'],'validateLength'],
		];
	}
	
	public function validateLength($attribute)
	{
		if (!preg_match('/^.{1,75}$/', $this->$attribute)) {
			$this->addError($attribute, 'must be no longer than 75 characters');
		}
	}
	
	public function upload()
	{
		if ($this->validate()) {
			$this->extension = $this->file->extension;
			$tmpsize =$this->file->size / 1024.0 / 1024.0;
			$this->size = round($tmpsize,3);
			
			// upload files via ftp //
			/*
			$ftp = new \yii2mod\ftp\FtpClient();
			$ftp->connect(Yii::$app->params['ftpHost']);
			$ftp->login(Yii::$app->params['ftpUsername'], Yii::$app->params['ftpPassword']);
				
			$this->remoteUrl = Yii::$app->params['ftpTargetDirectory'] . "/" . $this->file->baseName . "." . $this->file->extension;
			$ftp->pasv(TRUE);
			$ftp->put($this->remoteUrl, $this->file->tempName, FTP_BINARY);
			
			
			if ($this->preview != '' && $this->preview != null ){
				$this->remotePreviewUrl = Yii::$app->params['ftpTargetPreviewDirectory'] . "/" . $this->preview->baseName . "." . $this->preview->extension;
				$ftp->put($this->remotePreviewUrl, $this->preview->tempName, FTP_BINARY);				
			}
			else{
				$this->remotePreviewUrl = "";
			}
				
			
			$total = $ftp->count();
			$ftp->close();*/
			
			/* upload files via HTTP */
			$this->remoteUrl = $this->file->baseName . "." . $this->file->extension;
			$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $this->remoteUrl;
			$this->file->saveAs($path);
			
			if ($this->preview != '' && $this->preview != null ){
				$this->remotePreviewUrl = $this->preview->baseName . "." . $this->preview->extension;
				$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $this->remotePreviewUrl;
				$this->preview->saveAs($path);
			}
			else{
				$this->remotePreviewUrl = "";
			}
			
				
			return true;
		} else {
			return false;
		}
		
	}
	
}

?>