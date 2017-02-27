<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use yii\web\UploadedFile;
use yii\base\Exception;

class AccountController extends \app\base\Controller {
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionRegistration() {
        $registrationForm = new RegistrationForm();

        if (\Yii::$app->request->isPost) {
            $registrationForm->load(\Yii::$app->request->post());
            $registrationForm->photo = UploadedFile::getInstance($registrationForm, 'photo');

            try {
                if ($registrationForm->run()) {
                    return \Yii::$app->user->login($registrationForm->user) ? $this->goAccount() : $this->goHome();
                }
            } catch (Exception $e) {
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('registration', ['registrationForm' => $registrationForm]);
    }
}