<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FileCategory */

$this->title = 'Create File Category';
$this->params['breadcrumbs'][] = ['label' => 'File Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-category-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>