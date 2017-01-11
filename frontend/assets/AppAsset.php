<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    	'css/papaya.css',
    	'css/default.css',
    	'css/jquery.selectBox.css',
    	'css/database.css',
    	'css/main.css',
    	'css/site.css',
    ];
    public $js = [
    	'js/papaya.js',
    	'js/jquery.dotdotdot.min.js',
    	'js/jquery.lazyload.min.js',
    	'js/jquery.cookie.js',
    	'js/zebra_datepicker.js', 
    	'js/jquery.selectBox.js',
    	'js/scripts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    	'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
