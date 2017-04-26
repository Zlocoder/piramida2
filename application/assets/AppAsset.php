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
    public $sourcePath = '@app/views/package';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/vegas.min.css',
        'css/style.css',
        'css/cabinet_style.css',
    ];
    public $js = [
        'js/jquery.countdown.min.js',
        'js/clipboard.min.js',
        'js/vegas.min.js',
        'js/main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
