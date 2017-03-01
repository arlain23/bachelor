<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/images/favicon.png" type="image/png" />
    
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<div id="myNav">
	    <?php
	    NavBar::begin([
	        'options' => [
	            'class' => 'navbar-custom',
	        ],
	    ]);
	    $menuItems = [
	        ['label' => 'Home', 'url' => ['/site/index']],
	    	['label' => 'File uploader', 'url' => ['/site/uploader']],
    		['label' => 'File manager', 'url' => ['/file-entry/index']],
	    	['label' => 'Categories', 'url' => ['/file-category/index']],
	    ];
	    if (Yii::$app->user->isGuest) {
	    	$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
	    } else {
	    	$menuItems[] = ['label' => 'Create new user', 'url' => ['/site/signup']];
	    	$menuItems[] = '<li class="li-logout">'
	    			. Html::beginForm(['/site/logout'], 'post')
	    			. Html::submitButton(
	    					'Logout (' . Yii::$app->user->identity->username . ')',
	    					['class' => 'btn btn-link logout btn-logout']
	    					)
	    					. Html::endForm()
	    					. '</li>';
	    }
	    
	    echo Nav::widget([
	        'options' => ['class' => 'navbar-custom-ul'],
	        'items' => $menuItems,
	    ]);
	    NavBar::end();
	    ?>
	</div>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>



<footer class="footer">
    <div class="container">
    		<p class="pull-left copyright">Aneta Andrzejewska <?= date('Y') ?></p>
        	<p  class="pull-right yii"><?= Yii::powered() ?></p>
    </div>
</footer>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
