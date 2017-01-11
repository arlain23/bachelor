<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use common\models\FileCategory;

/* @var $this yii\web\View */
/* @var $model common\models\FileEntry */
/* @var $form yii\widgets\ActiveForm */

/* TODO maybe: take existing fileEntryCategories and put them on the right sight of the form */
?>

<div class="file-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patient')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
    
    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
    
    <select multiple="multiple" id="fileentry-categories-select">
    	 	<?php 
			$fileCategories = FileCategory::find()->all();   
    	 	foreach ($fileCategories as &$value ){
    	 		?>
    	 			<option value="<?= $value->categoryID ?>"><?=$value->categoryName?></option>	 		
    	 		<?php 
    	 	}	 
    	 ?>  	
    </select>
    
    <?= $form->field($model, 'createDate')->textInput(['readonly' => true]) ?>
        
    <?= $form->field($model, 'fileURL')->textarea(['rows' => 1,'readonly' => true]) ?>

    <?= $form->field($model, 'gifURL')->textarea(['rows' => 1,'readonly' => true]) ?>

    <?= $form->field($model, 'fileExtension')->textInput(['maxlength' => true,'readonly' => true]) ?>

    <?= $form->field($model, 'fileSize')->textInput(['readonly' => true]) ?>
    
    
    <?= $form->field($model, 'categories')->hiddenInput(['value'=>0])->label(false) ?>  
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>