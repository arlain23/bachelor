<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\FileEntry */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'File Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->fileEntryId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->fileEntryId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fileEntryId',
            'title',
            'createDate',
            'patient',
            'fileURL:ntext',
            'gifURL:ntext',
            'fileExtension',
            'fileSize',
            'description:ntext',
            'content:ntext',
        ],
    ]) ?>

</div>
