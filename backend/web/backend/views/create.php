<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\FileEntry */

$this->title = 'Create File Entry';
$this->params['breadcrumbs'][] = ['label' => 'File Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
