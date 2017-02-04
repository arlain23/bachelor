<?php header('Access-Control-Allow-Origin: *'); ?>

<?php

/* @var $this yii\web\View */

$this->title = 'file uploader';

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<script>
	inicialiseControllerPath("<?= \Yii::$app->getUrlManager()->createUrl('site/ajax') ?>");
</script>
   
<div class="switch-view-button">
   	<?= Html::a('Upload single file', ['uploader'], ['class' => 'btn btn-primary']) ?>				    		
</div>
<div class="ftp col-md-6 col-sm-6 col-xs-6">
	
	<?php 
	$form = ActiveForm::begin([
			'id' => 'multiple-file-form',
			'options' => [
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data',
			],
	])
	
	
	?>
    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'gifUniqueId')->hiddenInput(['value'=>0])->label(false) ?> 
    <div class="slider-container-multiple">
        <?= $form->field($model, 'isPrivate')->hiddenInput(['value'=>0])->label(false) ?> 
	    <div class="slider-text slider-text-before">public</div>
		<label class="switch">
		  <input type="checkbox">
	  	  <div class="slider round"></div>
		</label>
	  	<div class="slider-text slider-text-after">private</div>
    </div>
    

       
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    

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

<?php ActiveForm::end() ?>


