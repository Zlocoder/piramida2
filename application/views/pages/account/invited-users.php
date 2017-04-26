<?php

/*
 * @var $this yii\web\View
*/

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'My cabinet');
$this->params['section_class'] = 'cabinet';
//$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];
?>

<div class="row">
    <main class="col-lg-12">
        <?= $this->render('chunks/left-side', [
            'account' => $account,
            'refLink' => $refLink,
            'bestUsers' => $bestUsers,
        ]) ?>


        <div class="col-lg-7">
        <h4>Invited users</h4>

        <table class="table">
            <tbody>
            <tr>
                <th>User</th>
                <th>Level</th>
                <th>Status</th>
                <th>Registrated</th>
            </tr>
            </tbody>

            <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?= $user->user->login ?></td>
                    <td><?= $user->user->position->level - $account->position->level ?></td>
                    <td><?= $user->user->status->isActive ? $user->user->status->status : '' ?></td>
                    <td><?= $user->user->created ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
            </div>

        <?= $this->render('chunks/right-side', [
            'counts' => $counts
        ]) ?>
    </main>
</div>

