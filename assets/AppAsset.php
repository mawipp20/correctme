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
    public $sourcePath = '@web';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/correctme.css',
        'css/site.css',
    ];
    public $js = [
        'js/correctme_form_elements.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        '\rmrevin\yii\fontawesome\AssetBundle',
    ];
    public $publishOptions = [
        'forceCopy'=>true,
    ];
}
