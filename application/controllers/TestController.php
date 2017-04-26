<?php

namespace app\controllers;

use app\models\Position;

class TestController extends \app\base\AdminController {
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

    public function fixTree($tree, &$counts = [], $level = 1) {

    }

    public function actionTree() {
        $position = Position::findOne(1);

        $tree = $this->buildTree($position, $position->getChilds()
            ->andWhere("id > 1")
            ->with(['user'])
            ->all());

        $tree = $tree->toArray(['id' => 'decbin', 'level', 'appended']);
        return '<script> console.log(' . json_encode($tree) . ') </script>';
    }
}