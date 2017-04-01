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
            <div class="row" style="padding-top: 20px;">
                <h4 class="text-center">Оплата статуса</h4>
                <div class="col-lg-8 col-lg-offset-2">
                    <form method="post">
                        <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />

                        <div class="form-group">
                            <label>Статус</label>
                            <select name="status" class="form-control">
                                <?php foreach ($options as $status => $amount) { ?>
                                    <option value="<?= $status ?>"><?= $status ?> - <?= $amount ?> USD</option>
                                <?php } ?>
                            </select>
                        </div>

                        <p class="text-danger"><b>Внимание!!! Чтобы статус активировался максимально быстро, обязательно, доведите процесс оплаты до конца. После оплаты вы будете перенаправлены в личный кабинет.</b></p>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary"><?= \Yii::t('app', 'Перейти к оплате') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?= $this->render('chunks/right-side', [
            'counts' => $counts
        ]) ?>
    </main>
</div>
