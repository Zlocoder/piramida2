<?php

/*
 * @var $this yii\web\View
*/

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'My cabinet');
$this->params['section_class'] = 'cabinet';
//$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];

\app\assets\TreeAsset::register($this);
?>

<div class="row">
    <main class="col-lg-12">
        <?= $this->render('chunks/left-side', [
            'account' => $account,
            'refLink' => $refLink,
            'bestUsers' => $bestUsers,
        ]) ?>

        <div class="col-sm-7">
            <div class="col-sm-12">
                <?= $this->render('chunks/tree-recursion', ['tree' => $tree, 'level' => 0]) ?>
                <?php $this->registerJs("
                    $('.tree-item').click(function() {
                        document.location = $(this).data('href');
                    });
                "); ?>
            </div>
        </div>

        <?= $this->render('chunks/right-side', [
            'counts' => $counts
        ]) ?>
    </main>
</div>
