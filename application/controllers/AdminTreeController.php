<?php

namespace app\controllers;

use app\models\Position;

class AdminTreeController extends \app\base\AdminController {
    public function buildTree($tree, $positions) {
        $id = (int)$tree->id;
        if (isset($positions[$id << 1])) {
            $tree->populateRelation('left', $this->buildTree($positions[$id << 1], $positions));
        }

        if (isset($positions[($id << 1) + 1])) {
            $tree->populateRelation('right', $this->buildTree($positions[($id << 1) + 1], $positions));
        }

        return $tree;
    }


    public function actionIndex($treeId = null) {
        $positions = Position::find()
            ->andWhere("level > 2")
            ->andWhere("level < 11")
            ->with(['user.status', 'user.invite', 'user.payment'])
            ->all();

        $trees = [];
        foreach ($positions as $position) {
            if ($position->level == 3) {
                $trees[$position->id] = $this->buildTree($position, $positions);
            }
        }

        return $this->render('tree', [
            'trees' => $trees
        ]);
    }
}
