<?php

namespace backend\models;

use Yii;

class MultipleFileForm extends \yii\base\Model
{
	
	/**
	 * @var UploadedFile[]
	 */
	
	public $files;
	public $titles = array();
	public $extensions = array();
	public $remoteUrls = array();
	public $remoteGifsUrls = array();
	public $sizes = array();
	public $isPrivate;
	public $gifUniqueId;
	public $gifUniqueIdArray = array();
	
	
	public function rules()
	{
		return [
				[['files','isPrivate','gifUniqueId'], 'required'],
				[['files'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 0],
		];
	}
	public function upload()
	{
		if ($this->validate()) {
			$index = 0;
			$gifUniqueIdArray = preg_split("/,/", $this->gifUniqueId);
			$gifIndex = 0;
			foreach ($this->files as $file) {
				$this->titles[$index] = $file->baseName;
				$this->extensions[$index] = $file->extension;
				if ($this->extensions[$index] == 'gz' && substr($file->baseName, -3) == 'nii'){
					$this->extensions[$index] = 'nii.gz';
					$this->titles[$index] = substr($this->titles[$index],0,-4);
					
				}
				
				$tmpsize = $file->size / 1024.0 / 1024.0;
				$this->sizes[$index] = round($tmpsize,3);
				$this->remoteUrls[$index] = time() . $file->baseName . "." . $file->extension ;
				
				$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $this->remoteUrls[$index];
				$file->saveAs($path);	
				
				//save gif
				if ($file->extension == 'nii' || $file->extension == 'nii.gz'){
					$directory = Yii::getAlias('@frontend') . '/web/images/uploads/' . $gifUniqueIdArray[$gifIndex];
					Yii::info($directory);
					$frames = array();
					$durations = array ();
					foreach(glob($directory.'/*.*') as $image)					{
						Yii::info("image pushed " . $image);
						array_push($frames, $image);
						array_push($durations, 20);
					}

					// Initialize and create the GIF
					if (count($frames) > 0){
						$gc = new \GifCreator\GifCreator();
						$gc->create($frames, $durations, 0);
							
						$gifBinary = $gc->getGif();
						Yii::info($gifBinary);
						$this->remoteGifsUrls[$index] = 'gifs/' . time() . $file->baseName . '.gif';
						$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $this->remoteGifsUrls[$index];
						file_put_contents($path, $gifBinary);
						Yii::info("gif created " . $path);
								
						$gifIndex += 1;
						
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
						$this->remoteGifsUrls[$index] = null;
					}
				}
				else{
					$this->remoteGifsUrls[$index] = null;
				}
				$index += 1;
			}
			return true;
		}
		else{
			return false;
		}
	}	
	
}

?>