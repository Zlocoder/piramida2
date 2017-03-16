<?php

namespace app\assets;

class TreeAsset extends \yii\web\AssetBundle {
    public $sourcePath = '@app/views/package';

    public $css = [
        'css/tree.css'
    ];

    public $js = [
        'js/tree.js'
    ];

    public $depends = [
        'app\assets\AppAsset'
    ];
}