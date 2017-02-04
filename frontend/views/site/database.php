<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\FileEntry;
use common\models\FileCategory;
use yii\widgets\LinkPager;
use common\models\Admin;

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
				<?=HTML::input("text","search-field",$searchField,['class'=>"search-input",'placeholder'=>"Search"])?>
				<span class="search-icon">&nbsp;</span>
				<?=HTML::submitButton('Search',['class'=>'btn btn-primary filter-submit-button'])?>
				<?=HTML::input("hidden","sort-radio",$sortType)?>
				<?=HTML::input("hidden","sort-direction",$asc)?>
				
				<?=HTML::endForm()?>
			</div>
		</div>
		<div class="filter-column">
			<?=HTML::beginForm([$action = 'site/database', $method = 'post'])?>
				<div class="filter-categories-select">
					<p class="database-title">
					Filter
					</p>
					<?=HTML::beginTag('select')?>	
						<?php 
						$fileCategories = FileCategory::find()->all();
						echo HTML::beginTag('option',['value'=>0,'selected' => ($selectCategories == 0 )]);
						echo "Select a category";
						echo HTML::endTag('option');
						
						foreach ($fileCategories as &$value ){
							echo HTML::beginTag('option',['value'=>$value->categoryID,'selected' => ($selectCategories == $value->categoryID )]);
								echo $value->categoryName;					
							echo HTML::endTag('option');
						}
						?>
					<?=HTML::endTag('select')?>
				</div>
				<?=HTML::input('hidden','select-categories',0,['class'=>'select-categories-hidden'])?>
				<div id="dateFrom">
						<?=HTML::label('Date from', 'dateFrom') ?>
						<?=HTML::input('text','dateFrom',$dateFrom,['class'=>'publishDateFrom datepicker onselect'])?>
				</div>
				<div id="dateTill">
					<?=HTML::label('Date till', 'dateTill') ?>
					<?=HTML::input('text','dateTill',$dateTill,['class'=>'publishDateTill datepicker onselect','label'=>'Date till'])?>
				</div>
				
				<?=HTML::input("hidden","sort-radio",$sortType)?>
				<?=HTML::input("hidden","sort-direction",$asc)?>
				
				<?=HTML::submitButton('Filter',['class'=>'btn btn-primary filter-submit-button'])?>
			<?=HTML::endForm()?>
		</div>
     	<div class="database-sort">
     		<p class="database-title">
				Sort
			</p>
			<div class="sort-form">
				<div class="sort-options">
					<?=HTML::beginForm([$action = 'site/database', $method = 'post'])?>	
					<ul>
						<li> 
							<?=HTML::input("radio","sort-radio","createDate",['id'=>'date-sort','checked' => ($sortType=="createDate")])?>
							<?=HTML::label("Date","date-sort")?>
							<div class="check"> </div>
						</li>
						<li> 	
							<?=HTML::input("radio","sort-radio","title",['id'=>'title-sort','checked' => ($sortType=="title")])?>
							<?=HTML::label("Title","title-sort")?>
							<div class="check"> </div>
						</li>
						<li> 
							<?=HTML::input("radio","sort-radio","fileSize",['id'=>'file-size-sort','checked' => ($sortType=="fileSize")])?>
							<?=HTML::label("File size","file-size-sort")?>
							<div class="check"> </div>
						</li>
					
					</ul>
				</div>
				<div class="sort-direction">
					<span>
						<?php ?>
						<?=HTML::input("radio","sort-direction",0,['id'=>'dsc-direction','checked' => ($asc==0)])?>
						<?=HTML::label(" ","dsc-direction",['class'=> "sort-direction-btn dsc-direction"])?>
					</span>
					<span>
						<?=HTML::input("radio","sort-direction",1,['id'=>'asc-direction','checked' => ($asc==1)])?>
						<?=HTML::label(" ","asc-direction",['class'=> "sort-direction-btn asc-direction"])?>
					</span>
				</div>
				<?=HTML::input("hidden","select-categories",$selectCategories)?>
				<?=HTML::input("hidden","dateFrom",$dateFrom)?>
				<?=HTML::input("hidden","dateTill",$dateTill)?>
				<?=HTML::input("hidden","search-field",$searchField)?>
				
				<?=HTML::submitButton('Sort',['class'=>'btn btn-primary filter-submit-button'])?>
				<?=HTML::endForm()?>
			</div>
			
		</div>
		
    </div>
    <div class="col-md-9 col-sm-9 col-xs-12 data-column">   
    	<?= Html::beginForm(['site/download-zipped-files'], 'post') ?>
    	<div class="download-button">
    		<?= Html::submitButton('Download files', ['class' => 'btn btn-primary fa fa-download ']) ?>
    	</div>
		<div class="total-count">
    		<p class="text-muted"> Total of <?= $totalCount?> entries </p>
    	</div>
		<a class="pick-all-btn"> 
	   		<i class="fa fa-check-square-o" aria-hidden="true"></i>
	   		Pick all
		</a>


    	<div class="view-icons">
    		<i class="fa fa-th-list view-icon-details-view" aria-hidden="true"></i>
    		<i class="fa fa-th-large view-icon-list-view" aria-hidden="true"></i>
    		
    	</div>
    	
    	<!--  View with only icons and details -->
    	<ul class="database-ul database-details-view">  
    	<?php if ( $totalCount == 0){ ?>
    		<li class="row file-entry file-data">
    			<p class="file-title no-entries"> <i> No entries found </i> </p>
    		</li>
    	
    	<?php }?>
		<?php foreach ($fileEntries as $fe): ?>
		    <li class="row file-entry">
	    		<?php 
	    			if ($fe->gifURL != ""){
	    				?>
	    					<div class="col-md-4 file-image-preview">
	    						<img alt="<?= $fe->title ?>" class="img-responsive-inverse lazy" data-original="<?= '/images/uploads/' . $fe->gifURL ?>">
	    				<?php
	    			}
	    			else{
	    				?>
	    				 	<div class="col-md-4 file-image-preview file-image-placeholder">
	    				<?php
	    			}
	    		?>

	    		</div>
		    	<div class="col-md-8 file-data dotdotdot">
		    		<p class="file-title"><?= Html::encode("{$fe->title}")?> </p>
		    		<p class="file-metadata text-muted">
		    			<?php 
		    				$date = new DateTime($fe->createDate);
		    				echo $date->format('d.m.Y');
		    				echo "  (" . $fe->fileSize . "MB   ." . $fe->fileExtension . ")";
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
		
		<!--  View with only icons and titles -->
		<ul class="database-ul database-list-view">  
    	<?php if ( $totalCount == 0){ ?>
    		<li class="row file-entry file-data">
    			<p class="file-title no-entries"> <i> No entries found </i> </p>
    		</li>
    	
    	<?php }?>
		<?php
		$index = 0;
		 foreach ($fileEntries as $fe):	    
    		if ( $index == 0 ){ ?>
	   				<li class="row file-entry">
			<?php }?>
			   <div class="col-md-4">
	    		<?php 
	    			if ($fe->gifURL != ""){
	    				?>
	    					<div class="file-image-preview">
	    						<img alt="<?= $fe->title ?>" class="img-responsive-inverse lazy" data-original="<?= '/images/uploads/' . $fe->gifURL ?>">
	    					</div>
	    				<?php
	    			}
	    			else{
	    				?>
	    				 	<div class="file-image-preview file-image-placeholder"></div>
	    				<?php
	    			}
	    		?>

	    		<div class="file-title">
	    			<?= Html::a(Html::encode($fe->title), ['details', 'fileId' => $fe->fileEntryId]) ?>			
	    		</div>
    		    <div class="download-checkbox list-view-checkbox">
    				<?= Html::input("checkbox","downloadCheck[]",$fe->fileEntryId) ?>
		    	</div>
    			
			   </div>
   				<?php $index += 1;?>
			   <?php if ( $index == 3){
			   	$index = 0;
			   		?>
	   				    </li>
			   		<?php 
			   }?>
		<?php endforeach; ?>
		</ul>
		
		<!--  End of view changes -->
		
		<?= LinkPager::widget(['pagination' => $pagination]) ?>
    	
	  	<?= Html::endForm() ?>
  </div>
</div>





