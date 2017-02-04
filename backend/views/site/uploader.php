<?php

/* @var $this yii\web\View */

$this->title = 'file uploader';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use common\models\FileCategory;
use kartik\switchinput\SwitchInput;

?>
<script>
	inicialiseControllerPath("<?= \Yii::$app->getUrlManager()->createUrl('site/ajax') ?>");
</script>
<div class="switch-view-button">
   	<?= Html::a('Upload multiple files', ['multiple-uploader'], ['class' => 'btn btn-primary']) ?>				    		
</div>
<div class="ftp">
	
	<?php 
	$form = ActiveForm::begin([
			'id' => 'file-form',
			'options' => [
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data',
			],
	])
	
	
	?>
	<?= $form->field($model, 'title') ?>
	<?= $form->field($model, 'patient') ?>
	<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

	<?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
    <div class="col-md-6 col-sm-6 col-xs-6">
       	<select multiple="multiple" id="fileform-categories-select">
    	 	<?php 
			$fileCategories = FileCategory::find()->all();   
    	 	foreach ($fileCategories as &$value ){
    	 		?>
    	 			<option value="<?= $value->categoryID ?>"><?=$value->categoryName?></option>	 		
    	 		<?php 
    	 	}	 
    	 ?>  	
    	</select>
    	

	    <?= $form->field($model, 'file')->fileInput()->label('File to upload') ?>
	    <?= $form->field($model, 'gifUniqueId')->hiddenInput(['value'=>0])->label(false) ?> 
	    <div class="slider-container">
	        <?= $form->field($model, 'isPrivate')->hiddenInput(['value'=>0])->label(false) ?> 
		    <div class="slider-text slider-text-before">public</div>
			<label class="switch">
			  <input type="checkbox">
		  	  <div class="slider round"></div>
			</label>
		  	<div class="slider-text slider-text-after">private</div>
	    </div>
	    
	    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	    <?= $form->field($model, 'categories')->hiddenInput(['value'=>0])->label(false) ?>  
	    
	    <div class="form-group">
	        <div class="col-md-12 submit-button">
	            <?= Html::submitButton('Send file', ['class' => 'btn btn-primary']) ?>
	        </div>
	    </div>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-6">
		<div id="logger"> </div>
		<canvas id="mipCanvas" width="256" height="256"></canvas>
	</div>
</div>

<?php ActiveForm::end() ?>
