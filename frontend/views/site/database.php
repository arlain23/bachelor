<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\FileEntry;
use yii\widgets\LinkPager;

$this->title = 'Database';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-database">
    <h1><?= Html::encode($this->title) ?></h1>
	<ul>
	<?php foreach ($fileEntries as $fe): ?>
	    <li class="row file-entry">
    		<?php 
    			if ($fe->gifURL != ""){
    				?>
    					<div class="col-md-5 file-image-preview">
    						<img class="img-responsive lazy" data-original="<?= '/images/uploads/' . $fe->gifURL ?>">
    				<?php
    			}
    			else{
    				?>
    				 	<div class="col-md-5 file-image-preview file-image-placeholder">
    				<?php
    			}
    		?>
    		</div>
	    	<div class="col-md-7 file-data dotdotdot">
	    		<p class="file-title"><?= Html::encode("{$fe->title}")?> </p>
	    		<p class="file-metadata text-muted">
	    			<?php 
	    				$date = new DateTime($fe->createDate);
	    				echo $date->format('d.m.Y');
	    				//echo $fe->fileSize;
	    				echo "  (" . $fe->fileExtension . ")";
	    			?>
	    		</p>
    			<p class="file-patient"><?= Html::encode("{$fe->patient}")?> </p>
	    		<p class="file-description">
	    			<?= Html::encode("{$fe->description}")?>
	    		</p>
	    		
	    		
	    		<div class="see-more-button"> 
	    			<?= Html::a('Read more', ['details', 'fileId' => $fe->fileEntryId], ['class' => 'btn btn-primary']) ?>		
	    		</div>
	    		
	    	</div>
			
	    </li>
	<?php endforeach; ?>
	</ul>
	<?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>





