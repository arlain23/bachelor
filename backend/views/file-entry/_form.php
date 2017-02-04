<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use common\models\FileCategory;
use common\models\FileEntryCategory;

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
    
    <?php 
    	$selectedFileCategories = FileEntryCategory::find()->andFilterWhere(["fileEntryID"=>$model->fileEntryId])->all();
    	$categoryIdsArray = array();
    	$categoryString = "";
    	$index = 0;
    	foreach ($selectedFileCategories as &$fc){
    		$categoryIdsArray[$index] = $fc->categoryID;
    		$categoryString = $categoryString . "," . $fc->categoryID;
    		$index += 1;
    	}
    ?>
    <select multiple="multiple" id="fileentry-categories-select">
    
    	 	<?php 
			$fileCategories = FileCategory::find()->all();   
    	 	foreach ($fileCategories as &$value ){
    	 		if (in_array($value->categoryID, $categoryIdsArray)) {
    	 			?>
    	 			<option value="<?= $value->categoryID ?>" selected="selected"><?=$value->categoryName?></option>	 	
    	 		
    	 		<?php 
    	 		}
    	 		else{ ?>
    	 			<option value="<?= $value->categoryID ?>"><?=$value->categoryName?></option>
    	 		<?php 
    	 		}
    	 	}	 
    	 ?>  	
    </select>
    
   	<div class="slider-container-fileentry">
	        <?= $form->field($model, 'isPrivate')->hiddenInput()->label(false) ?> 
		    <div class="slider-text slider-text-before">public</div>
			<label class="switch">
			  <input type="checkbox">
		  	  <div class="slider round"></div>
			</label>
		  	<div class="slider-text slider-text-after">private</div>
	</div>
	    
    <?= $form->field($model, 'createDate')->textInput(['readonly' => true]) ?>
        
    <?= $form->field($model, 'fileURL')->textarea(['rows' => 1,'readonly' => true]) ?>

    <?= $form->field($model, 'gifURL')->textarea(['rows' => 1,'readonly' => true]) ?>

    <?= $form->field($model, 'fileExtension')->textInput(['maxlength' => true,'readonly' => true]) ?>

    <?= $form->field($model, 'fileSize')->textInput(['readonly' => true]) ?>
    
    
    
    <?= $form->field($model, 'categories')->hiddenInput(['value'=>$categoryString])->label(false) ?>  
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>