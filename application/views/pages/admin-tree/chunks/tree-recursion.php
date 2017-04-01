<div class="row tree">
    <div class="col-sm-12">
        <div data-href="<?= \yii\helpers\Url::to(['admin-tree']) ?>" class="tree-item level-<?= $level ?> <?= $tree->user->status->isActive ? $tree->user->status->status : null ?>">
            <div class="circle1">
                <div class="circle2">
                    <div class="circle3">
                        <?php if ($tree->user->hasPhoto) { ?>
                            <img src="<?= $tree->user->getPhotoUrl([50, 50]) ?>" />
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="description">
                <p><b>Логин:</b> <?= $tree->user->login ?></p>

                <p><b>Email:</b> <?= $tree->user->email ?></p>

                <p><b>Уровень:</b> <?= $tree->level ?></p>

                <p><b>Статус:</b> <?= $tree->user->status->isActive ? $tree->user->status->status : 'Неактивен' ?></p>

                <p><b>Приглашенных:</b> <?= $tree->user->invite->count ?></p>

                <p><b>В дереве:</b> <?= $tree->user->position->total ?></p>

                <p><b>Зарегистрирован:</b> <br/> <?= $tree->user->created ?></p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 tree-left">
        <?php if ($tree->isRelationPopulated('left')) { ?>
            <?= $this->render('tree-recursion', ['tree' => $tree->left, 'level' => $level + 1]) ?>
        <?php } ?>
    </div>

    <div class="col-sm-6 tree-right">
        <?php if ($tree->isRelationPopulated('right')) { ?>
            <?= $this->render('tree-recursion', ['tree' => $tree->right, 'level' => $level + 1]) ?>
        <?php } ?>
    </div>
</div>