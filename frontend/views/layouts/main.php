<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
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
    <?php $this->head()?>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/images/favicon.png" type="image/png" />
    
</head>
<body>
<?php $this->beginBody() ?>

<header id="banner" role="banner">
	 <div class="container header-main"> 
	 	<div class="col-md-8 col-sm-8 col-xs-12 main-logo header-left">
 	 		<a href=<?=Yii::$app->getHomeUrl();?>>
		 		 <div class="site-logo col-md-2 col-sm-2 col-xs-3 col-xxs-4">		 	
	 		 	 	<img class="logo-img img-responsive" src="images/logo-ie.png" alt="home page">		  
		 		 </div>
				<div class="subtitle col-md-10 col-sm-10 col-xs-9 col-xxs-8">
					 <p>Institute of Electronics</p> 
				</div>
		 	</a>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12 text-center header-right">
			<div id="display-controls" class="display-controls"> 
				<a title="big font" href="#" id="font-size-large" class="control-button">A</a>
				<a title="middle font" href="#" id="font-size-regular" class="control-button">A</a>
				<a title="small font" href="#" id="font-size-small" class="control-button">A</a>
				<span class="toggle-contast-span"> 
					<a title="toggle contrast" href="#" id="toggle-contrast">
					<i class="fa-adjust fa"></i>
					</a> 
				</span>
			</div> 
		</div> 
	</div>
</header>

<div class="wrap">
    <?php
    NavBar::begin([
        'options' => [
            'class' => 'navbar-custom',
        ],
    ]);
    $menuItems = [
        /*['label' => 'Home', 'url' => ['/site/index']],*/
        /*['label' => 'About', 'url' => ['/site/about']],*/
        /*['label' => 'Contact', 'url' => ['/site/contact']],*/
    	['label' => 'Database', 'url' => ['/site/database']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
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
    	<div class="col-md-5 col-sm-5 col-xs-12 link-banners">
    		<a href="https://www.p.lodz.pl/en">
    			<img class="img-responsive" alt="TUL" src="images/tulFooter.png"/>
    		</a>
    		<a href="http://www.eletel.p.lodz.pl/eng/">
    			<img class="img-responsive" alt="eletel" src="images/wrseeia-logo.png"/>
    		</a>
    		<a href="http://ife.p.lodz.pl/en">
    			<img class="img-responsive" alt="IFE" src="images/ifeFooter.png"/>
    		</a>
    	</div>
    	<div class="col-md-7 col-sm-7 col-xs-12 powered-by">
    		<span class="copyright">Aneta Andrzejewska <?= date('Y') ?></span>
        	<span  class="yii"><?= Yii::powered() ?></span>
    	</div>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
