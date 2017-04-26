<?php

namespace app\controllers;

use app\models\Position;

class AdminTreeController extends \app\base\AdminController {
    public function actionIndex() {
        $request = \Yii::$app->request;

        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($treeId = $request->get('treeId')) {
                $position = Position::find()->where(['id' => $treeId])->with('left, right, user.status, user.invite, user.payment')->one();

                if ($position) {
                    return $position;
                }

                return [
                    'error' => 'Not found position ' . $treeId
                ];
            }

            return [
                'error' => 'Not set position'
            ];
        }

        $position = Position::find()->where(['id' => 1])->with('left, right, user.status, user.invite, user.payment')->one();

        return $this->render('tree', [
            'position' => $position
        ]);
    }
}
