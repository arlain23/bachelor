<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FileCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'File Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-category-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create File Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'categoryID',
            'categoryName',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>