<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FileEntry */

$this->title = 'Update File Entry: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'File Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->fileEntryId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="file-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>