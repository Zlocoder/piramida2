<?php

/*
 * @var $this yii\web\View
*/

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Tree users');
$this->params['breadcrumbs'][] = ['label' => 'Tree users'];

\app\assets\TreeAsset::register($this);
?>
</div>
<div class="fluid-container" style="display: inline-block; min-width: 1920px;">
    <div class="row">
        <?php foreach ($trees as $tree) { ?>
            <div class="col-sm-3 admin">
                <?= $this->render('chunks/tree-recursion', ['tree' => $tree, 'level' => 3]) ?>
            </div>
        <?php } ?>
        <?php $this->registerJs("
        $('.tree-item').click(function() {
            document.location = $(this).data('href');
        });
    "); ?>
    </div>
</div>

<div class="container">

