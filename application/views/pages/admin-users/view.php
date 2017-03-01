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
    <h1><?= \Yii::t('app', 'User') . " {$user->id}" ?></h1>

</div>