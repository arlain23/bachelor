<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class NiftiAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
			'js/nifti-reader.js',
			'js/nifti.helper.js',
			'js/xtk.js',
	];
	public $depends = [
	];
	public $jsOptions = [
			'position' => \yii\web\View::POS_HEAD
	];
}
