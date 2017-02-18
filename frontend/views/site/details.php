<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\FileEntry;
use frontend\assets\NiftiAsset;
NiftiAsset::register($this);


$this->title = 'File details';
$this->params['breadcrumbs'][] = $this->title;
$isGuest = Yii::$app->user->isGuest;
if (!($isGuest && $isPrivate)){
	
	?>
	<script type="text/javascript">
	        var params = [];
	        params["worldSpace"] = true;
	        params["images"] = ["<?= '/images/uploads/' . $fileEntry->fileURL ?>"];
	        params["expandable"] = true;
	</script>
	
	
	<div class="file-details row">
		<div class="col-md-11 col-sm-11 col-xs-11">
			<div class="row">
		
	    		<?php 
	    			if ($fileEntry->gifURL != ""){
	    				?>
	    					<div class="col-md-4 col-sm-4 col-xs-4 file-image-preview">
	    						<img alt="<?= $fileEntry->title ?>" class="img-responsive-inverse lazy" data-original="<?= '/images/uploads/' . $fileEntry->gifURL ?>">
	    					</div>
	    					<div class="col-md-8 col-sm-8 col-xs-8">
	    				<?php
	    			}
	    			else{
	    				?>
	    				 	<div class="col-md-12 col-sm-12 col-xs-12">
	    				<?php
	    			}
	    		?>
	    		
			    
				    <p class="file-title">
				    	<?= Html::encode($fileEntry->title) ?>
				    </p>
		    	   <p class="file-date text-muted">
				   	   <?php 
				    		$date = new DateTime($fileEntry->createDate);
				    		echo $date->format('d.m.Y');
				    	?>
			    	</p>
				   	<p class="file-patient lead"><?= Html::encode("{$fileEntry->patient}")?> </p>
				   	<p class="file-description lead">
				    	<?= Html::encode("{$fileEntry->description}")?>
				    </p>
			    </div>
			</div>
			<div class="row">
			   <div class="col-md-12 col-sm-12 col-xs-12 file-content">
		    		<?= $fileEntry->content ?>
		   		</div>
			</div>
	
	   </div>
	   <div class="col-md-1 col-sm-1 col-xs-1">
	   <?= Html::a('', ['download-file', 'fileId' => $fileEntry->fileEntryId], ['class' => 'fa fa-download icon-download']) ?>		
	   </div>
	</div>
	<div class="file-metadata text-muted">
		<p> <?=$fileEntry->fileSize . "MB  (" . $fileEntry->fileExtension . ")"?></p>
	</div>
	
	<?php 
	if ($isPapayable){ ?>
		<script>
			setHeaderData("<?= '/images/uploads/' . $fileEntry->fileURL ?>");
		</script>
		<div class="col-md-4 col-sm-4 col-xs-12">
			<p class="nifti-header-info"> Nifti header information:</p>
			<div id="niftiHeader"> </div>
		</div>
		<div class="papaya-container col-md-8 col-sm-8 col-xs-12">
		
			<div class="papaya papaya-viewer" data-params="params"></div>
		</div>
	
	<?php }?>
	
	<?php 
	if ($isVTK){ ?>
	
		<div class="frame-wrapper">
			<iframe id="targetFrame" src ="vtk.htm" scrolling="no">
			</iframe>
		</div>	
		<script>
		window.onload = function() {
		document.getElementById('targetFrame').contentDocument.defaultView.showMesh("<?= '/images/uploads/' . $fileEntry->fileURL ?>");
		}
			//var el = document.getElementById('targetFrame');
			//getIframeWindow(el).defaultView.showMesh("<?= '/images/uploads/' . $fileEntry->fileURL ?>");/*
		</script>
	<?php }
}
else {
	?>
	<div class="alert alert-danger">
  		You are not authorised to view this site
	</div>
	
	<?php 
}
	
	
	?>
	




