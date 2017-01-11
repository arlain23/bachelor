<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\FileEntry;
use yii\widgets\LinkPager;

$this->title = 'File details';
$this->params['breadcrumbs'][] = $this->title;

?>
<script type="text/javascript">
        var params = [];
        params["worldSpace"] = true;
        params["images"] = ["<?= '/images/uploads/' . $fileEntry->fileURL ?>"];
        params["kioskMode"] = true;
</script>

<div class="file-details row">
	<div class="col-md-11 col-sm-11 col-xs-11">
	    <p class="file-title">
	    	<?= Html::encode($fileEntry->title) ?>
	    </p>
	    <div class="image-preview">
	     	<img class="img-responsive lazy" data-original="<?= '/images/uploads/' . $fileEntry->gifURL ?>">
	    </div>
	   <p class="file-date text-muted">
	   	   <?php 
	    		$date = new DateTime($fileEntry->createDate);
	    		echo $date->format('d.m.Y');
	    	?>
	   	<p class="file-patient lead"><?= Html::encode("{$fileEntry->patient}")?> </p>
	   	<p class="file-description lead">
	    	<?= Html::encode("{$fileEntry->description}")?>
	    </p>
	   <div class="file-content">
	    	<?= $fileEntry->content ?>
	   </div>
   </div>
   <div class="col-md-1 col-sm-1 col-xs-1">
   <?= Html::a('', ['download-file', 'fileId' => $fileEntry->fileEntryId], ['class' => 'fa fa-download icon-download']) ?>		
   </div>
</div>
<div class="file-metadata text-muted">
	<p> <?=$fileEntry->fileSize . "MB  (" . $fileEntry->fileExtension . ")"?></p>
</div>
<div class="papaya papaya-viewer" data-params="params"></div>







