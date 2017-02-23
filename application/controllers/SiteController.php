<?php

namespace app\controllers;

class SiteController extends \app\base\Controller
{
    public function actionIndex()
    {
        return $this->render('/index');
    }
}
