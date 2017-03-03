<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use yii\web\UploadedFile;
use yii\base\Exception;

class AccountController extends \app\base\Controller {
    public function actionIndex() {
        return $this->render('index', [
            'account' => $this->user->identity,
            'counts' => $this->user->position->counts
        ]);
    }

    public function actionRegistration() {
        $registrationForm = new RegistrationForm();
        $registrationForm->inviteId = \Yii::$app->session->get('inviteId', null);
        $registrationForm->inviteDate = \Yii::$app->session->get('inviteDate', date('Y-m-d H:i:s'));

        if (\Yii::$app->request->isPost) {
            $registrationForm->load(\Yii::$app->request->post());
            $registrationForm->photo = UploadedFile::getInstance($registrationForm, 'photo');

            try {
                if ($registrationForm->run()) {
                    \Yii::$app->session->remove('inviteId');
                    \Yii::$app->session->remove('inviteDate');
                    
                    return \Yii::$app->user->login($registrationForm->user) ? $this->goAccount() : $this->goHome();
                }
            } catch (Exception $e) {
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('registration', ['registrationForm' => $registrationForm]);
    }
}