<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use common\models\FileCategory;
use common\models\FileEntryCategory;

/* @var $this yii\web\View */
/* @var $model common\models\FileEntry */
/* @var $form yii\widgets\ActiveForm */


/* decide whether to show geometry or not */
$isVTK = false;
$acceptedFormats = [
		"stl",
		"vtk",
];
if (in_array($model->fileExtension, $acceptedFormats)) {
	$isVTK = true;
};


?>

<script>
	inicialiseControllerPath("<?= \Yii::$app->getUrlManager()->createUrl('file-entry/gajax') ?>");
	inicialiseImagePath("<?= 'geometry/' . time() . $model->title . '.png' ?>");
</script>

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
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-6">
		    <?= $form->field($model, 'createDate')->textInput(['readonly' => true]) ?>
		        
		    <?= $form->field($model, 'fileURL')->textarea(['rows' => 1,'readonly' => true]) ?>
		
		    <?= $form->field($model, 'gifURL')->textarea(['rows' => 1,'readonly' => true]) ?>
		
		    <?= $form->field($model, 'fileExtension')->textInput(['maxlength' => true,'readonly' => true]) ?>
		
		    <?= $form->field($model, 'fileSize')->textInput(['readonly' => true]) ?>
		      
		    <?= $form->field($model, 'categories')->hiddenInput(['value'=>$categoryString])->label(false) ?>  
	    
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6">
			<?php 
				if ($isVTK){ ?>
					<div class="frame-wrapper">
						<iframe id="targetFrame" src ="vtkGeo.php" scrolling="no" >
						</iframe>
					</div>
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-3">
							<p id="logger" class="text-muted"> </p>
						</div>
						<div class="uri-button col-md-9 col-sm-9 col-xs-9">
							<?= Html::button("set preview",['class' =>'btn btn-primary']) ?>
						</div>
					</div>	

					<script>
						window.onload = function() {
							document.getElementById('targetFrame').contentDocument.defaultView.showMesh("<?=  'http://frontend.dev//images/uploads/' . $model->fileURL ?>");
						}
					</script>
				<?php }
			?>
		
		</div>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>