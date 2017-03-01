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
            'id',
            'login',
            'fullname',
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