<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\FileEntrySearcher */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="file-entry-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'fileEntryId') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'createDate') ?>

    <?= $form->field($model, 'patient') ?>

    <?= $form->field($model, 'fileURL') ?>

    <?php // echo $form->field($model, 'gifURL') ?>

    <?php // echo $form->field($model, 'fileExtension') ?>

    <?php // echo $form->field($model, 'fileSize') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'content') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>