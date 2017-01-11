<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\FileEntrySearcher */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'File Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fileEntryId',
            'title',
            'createDate',
            'patient',
            //'fileURL:ntext',
            // 'gifURL:ntext',
            // 'fileExtension',
            // 'fileSize',
            // 'description:ntext',
             'content:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>