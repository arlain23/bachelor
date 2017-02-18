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
	public $gifUniqueId;
	public $content;
	public $categories;
	public $size;
	public $isPrivate;

	
	public function rules()
	{
		return [
				// define validation rules here
				[['title','categories','isPrivate','gifUniqueId'], 'required'],
				[['file'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType'=>false],
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
			if ($this->extension == 'gz' && substr($this->file->baseName, -3) == 'nii'){
				$this->extension = 'nii.gz';
				
			}
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
			$this->remoteUrl = time() . $this->file->baseName . "."  . $this->file->extension ;
			$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $this->remoteUrl;
			$this->file->saveAs($path);
			
			/*
			if ($this->preview != '' && $this->preview != null ){
				$this->remotePreviewUrl = $this->preview->baseName . "." . $this->preview->extension;
				$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $this->remotePreviewUrl;
				$this->preview->saveAs($path);
			}
			else{
				$this->remotePreviewUrl = "";
			}
			*/
			
			//save gif
			$gifUniqueIdArray = preg_split("/,/", $this->gifUniqueId);
			if ($this->file->extension == 'nii' || $this->file->extension == 'nii.gz'){
				$directory = Yii::getAlias('@frontend') . '/web/images/uploads/' . $gifUniqueIdArray[0];
				Yii::info($directory);
				$frames = array();
				$durations = array ();
				foreach(glob($directory.'/*.*') as $image)					{
					Yii::info("image pushed " . $image);
					array_push($frames, $image);
					array_push($durations, 20);
				}
				if (count($frames) > 0){
					// Initialize and create the GIF
					$gc = new \GifCreator\GifCreator();
					$gc->create($frames, $durations, 0);
				
					$gifBinary = $gc->getGif();
					Yii::info($gifBinary);
					$this->remotePreviewUrl = 'gifs/' . time() . $this->file->baseName . '.gif';
					$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $this->remotePreviewUrl;
					file_put_contents($path, $gifBinary);
					Yii::info("gif created " . $path);
						
						
					if (is_dir($directory)) {
						$objects = scandir($directory);
						foreach ($objects as $object) {
							if ($object != "." && $object != "..") {
								if (filetype($directory."/".$object) == "dir") rrmdir($directory."/".$object); else unlink($directory."/".$object);
							}
						}
						reset($objects);
						rmdir($directory);
					}
				}
				else{
					$this->remotePreviewUrl = null;
				}
			}
			else{
				$this->remotePreviewUrl = null;
			}
		
			return true;
		} else {
			return false;
		}
		
	}
	
}

?>