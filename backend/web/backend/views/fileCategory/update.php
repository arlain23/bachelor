<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FileCategory */

$this->title = 'Update File Category: ' . $model->categoryID;
$this->params['breadcrumbs'][] = ['label' => 'File Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->categoryID, 'url' => ['view', 'id' => $model->categoryID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="file-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
