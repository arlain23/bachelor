<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    	'css/main.css',
    	'css/multi-select.css',
    ];
    public $js = [
    	'js/nifti-reader.js',
    	'js/scripts.js',
    	'js/jquery.multi-select.js',
    	'js/mip-maker.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [
    		'position' => \yii\web\View::POS_HEAD
    ];
}
