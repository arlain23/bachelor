<?php

/* @var $this yii\web\View */

$this->title = 'file uploader';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use common\models\FileCategory;

?>
   

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
    <?= $form->field($model, 'preview')->fileInput()->label('GIF to upload') ?>
    
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <?= $form->field($model, 'categories')->hiddenInput(['value'=>0])->label(false) ?>  
    

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Send file', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>
