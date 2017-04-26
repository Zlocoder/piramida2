<div class="row tree">
    <div class="col-sm-12">
        <div data-href="<?= \yii\helpers\Url::to(['account/tree', 'treeId' => $tree->id]) ?>" class="tree-item level-<?= $level ?> <?= $tree->user->status->isActive ? $tree->user->status->status : null ?>">
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

                <p><b>Статус:</b> <?= $tree->user->status->isActive ? $tree->user->status->status : 'Неактивен' ?></p>

                <p><b>Приглашенных:</b> <?= $tree->user->invite->count ?></p>

                <p><b>В дереве:</b> <?= $tree->user->position->total ?></p>

                <p><b>Зарегистрирован:</b> <br/> <?= $tree->user->created ?></p>
            </div>
        </div>
    </div>

    <?php
        $hasLeft = $tree->isRelationPopulated('left');
        $hasRight = $tree->isRelationPopulated('right');
    ?>

    <?php if ($hasLeft) { ?>
        <div class="col-sm-12 arrow-container level-<?= $level ?>">
            <div class="arrow-item">
                <div class="dummy"></div>
                <div class="arrow arrow-left"></div>
            </div>
        </div>
    <?php } ?>

    <?php if ($hasRight) { ?>
        <div class="col-sm-12 arrow-container level-<?= $level ?>">
            <div class="arrow-item">
                <div class="dummy"></div>
                <div class="arrow arrow-right"></div>
            </div>
        </div>
    <?php } ?>

    <?php if ($hasLeft) { ?>
        <div class="col-sm-6 tree-left">
            <?= $this->render('tree-recursion', ['tree' => $tree->left, 'level' => $level + 1]) ?>
        </div>
    <?php } ?>

    <?php if ($hasRight) { ?>
        <div class="col-sm-6 tree-right">
            <?= $this->render('tree-recursion', ['tree' => $tree->right, 'level' => $level + 1]) ?>
        </div>
    <?php } ?>
</div>