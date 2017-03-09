<?php

/*
 * @var $this yii\web\View
 * @var $filter app\models\forms\UsersFilter
 * @var $provider yii\data\ActiveDataProvider
*/

use yii\grid\GridView;
use yii\grid\ActionColumn;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Users');

$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];
?>

<div class="row">
    <h1><?= \Yii::t('app', 'Users') ?></h1>

    <?= GridView::widget([
        'filterModel' => $filter,
        'dataProvider' => $provider,
        'dataColumnClass' => 'app\base\DataColumn',
        'columns' => [
            [
                'label' => 'active',
                'value' => function($model) {
                    if ($model->status->isActive) {
                        return \yii\bootstrap\Html::a('Выключить', '', [
                            'data-user' => $model->id,
                            'class' => 'btn btn-primary activity'
                        ]);
                    } else {
                        return \yii\bootstrap\Html::a('Включить', '', [
                            'data-user' => $model->id,
                            'class' => 'btn btn-default activity'
                        ]);
                    }
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'id',
                'filter' => false
            ],
            'login',
            [
                'label' => 'Invited by',
                'value' => 'invite.parentUser.login'
            ],
            [
                'attribute' => 'invite',
                'label' => \Yii::t('app', 'Invited count'),
                'value' => 'invite.count'
            ],
            'created',
            [
                'class' => ActionColumn::className()
            ]
        ]
    ]) ?>
</div>

<form id="activity-form" method="post">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />
    <input type="hidden" name="activity" value="" />
    <button type="submit" style="display: none;"></button>
</form>

<?php
    $this->registerJs("
        $('a.activity').click(function(e) {
            e.preventDefault();
            $('#activity-form input[name=activity]').val($(this).data('user'));
            $('#activity-form button').click();
        }); 
    ");
?>