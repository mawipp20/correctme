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
class StudentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/student_think.css',
    ];
    
    public $js = [
        'js/student_think.js',
        'js/jquery.autogrowtextarea.min.js',
        'js/jquery-progresspiesvg/js/min/jquery-progresspiesvg-min.js',
    ];
}
