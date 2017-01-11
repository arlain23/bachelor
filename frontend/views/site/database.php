<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\FileEntry;
use common\models\FileCategory;
use yii\widgets\LinkPager;

$this->title = 'Database';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-database">
    	<div class="instruction text-muted">
    		<p>On this page you can download the results of projects' research.</p>
    		<p>Mark the checkboxes on files' images that you wish to download and click the button.</p>
    	</div>
    	
    <div class="col-md-3 col-sm-3 col-xs-12 ">	
     	<div class="database-search">
     		<p class="database-title">
				Search
			</p>
			<div class="search-input-form">
				<?=HTML::beginForm([$action = 'site/database', $method = 'post'])?>		
				<?=HTML::input("text","search-field","",['class'=>"search-input",'placeholder'=>"Search"])?>
				<span class="search-icon">&nbsp;</span>
				<?=HTML::submitButton('Search',['class'=>'btn btn-primary filter-submit-button'])?>
				<?=HTML::endForm()?>
			</div>
		</div>
		<div class="filter-column">
			<?=HTML::beginForm([$action = 'site/database', $method = 'post'])?>
				<div class="filter-categories-select">
					<p class="database-title">
					Filters
					</p>
					<?=HTML::beginTag('select')?>	
						<?php 
						$fileCategories = FileCategory::find()->all();
						echo HTML::beginTag('option',['value'=>0]);
						echo "Select a category";
						echo HTML::endTag('option');
						
						foreach ($fileCategories as &$value ){
							echo HTML::beginTag('option',['value'=>$value->categoryID]);
								echo $value->categoryName;					
							echo HTML::endTag('option');
						}
						?>
					<?=HTML::endTag('select')?>
				</div>
				<?=HTML::input('hidden','select-categories',0,['class'=>'select-categories-hidden'])?>
				<div id="dateFrom">
						<?=HTML::label('Date from', 'dateFrom') ?>
						<?=HTML::input('text','dateFrom',"",['class'=>'publishDateFrom datepicker onselect'])?>
				</div>
				<div id="dateTill">
					<?=HTML::label('Date till', 'dateTill') ?>
					<?=HTML::input('text','dateTill',"",['class'=>'publishDateTill datepicker onselect','label'=>'Date till'])?>
				</div>
				<?=HTML::submitButton('Filter',['class'=>'btn btn-primary filter-submit-button'])?>
			<?=HTML::endForm()?>
		</div>
    </div>
    <div class="col-md-9 col-sm-9 col-xs-12 data-column">   
    	<?= Html::beginForm(['site/download-zipped-files'], 'post') ?>
    	<div class="download-button">
    		<?= Html::submitButton('Download files', ['class' => 'btn btn-primary fa fa-download ']) ?>
    	</div>

    	
    	<ul class="database-ul">  
		<?php foreach ($fileEntries as $fe): ?>
		    <li class="row file-entry">
	    		<?php 
	    			if ($fe->gifURL != ""){
	    				?>
	    					<div class="col-md-5 file-image-preview">
	    						<img class="img-responsive-inverse lazy" data-original="<?= '/images/uploads/' . $fe->gifURL ?>">
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
		    		
    		    	<div class="download-checkbox">
    					<?= Html::input("checkbox","downloadCheck[]",$fe->fileEntryId) ?>
		    		</div>
		    		<div class="see-more-button"> 
		    			<?= Html::a('Read more', ['details', 'fileId' => $fe->fileEntryId], ['class' => 'btn btn-primary']) ?>		
		    		</div>
		    		
		    	</div>
				
		    </li>
		<?php endforeach; ?>
		</ul>
		<?= LinkPager::widget(['pagination' => $pagination]) ?>
    	
	  	<?= Html::endForm() ?>
  </div>
</div>





