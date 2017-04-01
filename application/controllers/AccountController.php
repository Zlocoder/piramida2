<?php

namespace app\controllers;

use app\models\forms\EditProfile;
use app\models\forms\ChangePassword;
use app\models\forms\RegistrationForm;
use app\models\Position;
use app\models\User;
use app\models\UserActivation;
use yii\web\UploadedFile;
use yii\base\Exception;

class AccountController extends \app\base\Controller {
    public $account;
    public $counts;
    public $reflink;

    public function behaviors() {
        return [
            'access' => 'app\components\AccountAccessControl'
        ];
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if ($action->id != 'registration') {
                $this->account = $this->user->identity;
                $this->counts = $this->user->position->counts;
                $this->reflink = 'http://' . \Yii::$app->request->hostName . \yii\helpers\Url::to(['site/invite', 'inviteId' => $this->user->login]);
            }
            return true;
        }

        return false;
    }

    public function actionIndex() {
        $time = \DateTime::createFromFormat('Y-m-d H:i:s', $this->user->status->active);
        $time = $time && ($time > date('Y-m-d H:i:s')) ? date_format($time, 'Y/m/d H:i:s') : null;
        $position = $this->account->position;

        return $this->render('index', [
            'time' => $time,
            'account' => $this->account,
            'childs' => $position ? $position->childUsers->all() : [],
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'total_count' => User::find()->count(),
            'last_users' => User::find()->with('status')->orderBy(['created' => SORT_DESC])->limit(20)->all(),
            'top_users' => User::find()->joinWith('invite')->with('payment')->orderBy(['invite.count' => SORT_DESC])->limit(20)->all(),
            'bestUsers' => User::find()->joinWith('invite')->with('invite', 'position')->orderBy(['invite.count' => SORT_DESC])->limit(15)->all(),
            'countries' => require(\Yii::getAlias('@app/base/countries.php'))
        ]);
    }

    public function actionRegistration() {
        $registrationForm = new RegistrationForm();
        $registrationForm->inviteId = \Yii::$app->session->get('inviteId', null);
        $registrationForm->inviteDate = \Yii::$app->session->get('inviteDate', date('Y-m-d H:i:s'));


        if (\Yii::$app->request->isPost) {
            \Yii::$app->session->setFlash('isPost', true);
            $registrationForm->load(\Yii::$app->request->post());
            $registrationForm->photo = UploadedFile::getInstance($registrationForm, 'photo');

            try {
                if ($registrationForm->run()) {
                    \Yii::$app->session->remove('inviteId');
                    \Yii::$app->session->remove('inviteDate');

                    $check = \Yii::$app->mailer->compose('welcome', [
                        'name' => $registrationForm->user->name,
                        'login' => $registrationForm->login,
                        'password' => $registrationForm->password,
                        'sponsor' => $registrationForm->parentInvite->user->login
                    ])->setFrom(\Yii::$app->params['mailFrom'])
                        ->setTo($registrationForm->email)
                        ->setSubject('Welcome to DIAMOND REWARDS')
                        ->send();

                    $check = \Yii::$app->mailer->compose('activation', [
                        'code' => $registrationForm->user->activation->code
                    ])->setFrom(\Yii::$app->params['mailFrom'])
                        ->setTo($registrationForm->email)
                        ->setSubject('Activate your account')
                        ->send();

                    return \Yii::$app->user->login($registrationForm->user) ? $this->goAccount() : $this->goHome();
                }
            } catch (Exception $e) {
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('registration', ['registrationForm' => $registrationForm]);
    }

    public function actionEditProfile() {
        $editProfile = new EditProfile(['user' => $this->user->identity]);
        $changePassword = new ChangePassword(['user' => $this->user->identity]);

        if (\Yii::$app->request->isPost) {
            try {
                if (\Yii::$app->request->post('EditProfile')) {
                    $editProfile->load(\Yii::$app->request->post());
                    $editProfile->photo = UploadedFile::getInstance($editProfile, 'photo');

                    if ($editProfile->run()) {
                        return $this->goAccount();
                    }
                }

                if (\Yii::$app->request->post('ChangePassword')) {
                    $changePassword->load(\Yii::$app->request->post());

                    if ($changePassword->run()) {
                        $this->user->logout();
                        return $this->goLogin();
                    }
                }
            } catch (Exception $e) {
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('edit-profile', [
            'account' => $this->account,
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'bestUsers' => User::find()->joinWith('invite')->with('invite', 'position')->orderBy(['invite.count' => SORT_DESC])->limit(15)->all(),
            'model' => $editProfile
        ]);
    }

    public function actionOrder() {
        if (!$this->user->active) {
            return $this->goAccount();
        }

        $userStatus = $this->user->status;

        $statusOptions = [];
        if ($userStatus) {
            switch($userStatus->isActive ? $userStatus->status : 'RUBY') {
                case 'TEST' :
                    $statusOptions['TEST'] = 0.01;
                case 'RUBY' :
                    $statusOptions['RUBY'] = 10;
                case 'EMERALD' :
                    $statusOptions['EMERALD'] = 25;
                case 'SAPHIRE' :
                    $statusOptions['SAPPHIRE'] = 50;
                case 'DIAMOND' :
                    $statusOptions['DIAMOND'] = 100;
            }
        } else {
            $statusOptions['RUBY'] = 10;
            $statusOptions['EMERALD'] = 25;
            $statusOptions['SAPPHIRE'] = 50;
            $statusOptions['DIAMOND'] = 100;
        }

        if (\Yii::$app->request->isPost) {
            $postStatus = \Yii::$app->request->post('status');
            if (array_key_exists($postStatus, $statusOptions) && $statusOptions[$postStatus] >= $statusOptions[$userStatus->status]) {
                \Yii::$app->session->setFlash('orderStatus', $postStatus);
                \Yii::$app->session->setFlash('orderAmount', $statusOptions[$postStatus]);
                \Yii::$app->session->setFlash('orderUserId', $this->user->id);

                return $this->redirect(['payment/index']);
            }
        }

        return $this->render('order-status', [
            'options' => $statusOptions,
            'account' => $this->account,
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'bestUsers' => User::find()->joinWith('invite')->with('invite', 'position')->orderBy(['invite.count' => SORT_DESC])->limit(15)->all(),
        ]);
    }

    public function actionPaymentHistory() {
        return $this->render('payment-history', [
            'account' => $this->account,
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'bestUsers' => User::find()->joinWith('invite')->with('invite', 'position')->orderBy(['invite.count' => SORT_DESC])->limit(15)->all(),
            'history' => $this->user->payment->getHistory()->with(['invoice.user'])->all()
        ]);
    }

    public function actionInvitedUsers() {
        return $this->render('invited-users', [
            'account' => $this->account,
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'bestUsers' => User::find()->joinWith('invite')->with('invite', 'position')->orderBy(['invite.count' => SORT_DESC])->limit(15)->all(),
            'users' => $this->user->invite->getChilds()->with('user.position')->all()
        ]);
    }

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

    public function actionTree($treeId = null) {
        $position = $treeId ? Position::findOne($treeId) : $this->user->position;

        return $this->render('tree', [
            'account' => $this->account,
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'bestUsers' => User::find()->joinWith('invite')->with('invite', 'position')->orderBy(['invite.count' => SORT_DESC])->limit(15)->all(),
            'tree' => $this->buildTree($position, $position->getChilds()
                ->andWhere("level - {$position->level} < 4")
                ->with(['user.status', 'user.invite'])
                ->all())
        ]);
    }

    public function actionActivation($code) {
        if ($activation = UserActivation::findOne(['code' => $code])) {
            $user = User::findOne($activation->userId);
            $user->active = 1;
            $user->save();
        }

        return $this->goAccount();
    }

    public function actionResendActivation() {
        \Yii::$app->mailer->compose('activation', [
            'code' => $this->account->activation->code
        ])->setFrom(\Yii::$app->params['mailFrom'])
            ->setTo($this->account->email)
            ->setSubject('Activate your account')
            ->send();

        $this->goAccount();
    }
}