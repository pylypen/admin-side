<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        '/plugins/datepicker/datepicker3.css',
        '/plugins/ionslider/ion.rangeSlider.css',
        '/plugins/ionslider/ion.rangeSlider.skinNice.css'
    ];
    public $js = [
        'js/index.js',
        '/plugins/datepicker/bootstrap-datepicker.js',
        '/plugins/ionslider/ion.rangeSlider.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
