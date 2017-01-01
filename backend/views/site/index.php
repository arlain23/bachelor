<?php

/* @var $this yii\web\View */

$this->title = 'backend';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
?>
   

<div class="ftp">
	<h3>Testing ftp</h3>
	
	<?php 
	echo $message;
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
	<?= $form->field($model, 'description')->textarea() ?>

	<?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
    
    <?= $form->field($model, 'file')->fileInput()->label('File to upload') ?>
    <?= $form->field($model, 'preview')->fileInput()->label('GIF to upload') ?>
    
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Send file', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>

<div class="site-index">

    <div class="jumbotron">
        <h1>backend!</h1>

        <p class="lead">bla bla .</p>

   </div>
</div>
