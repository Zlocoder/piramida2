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

        $childs = $this->account->position->childUsers->prepare(\Yii::$app->db->queryBuilder)->createCommand();
        $sql = $childs->rawSql;

        return $this->render('index', [
            'time' => $time,
            'account' => $this->account,
            'childs' => $this->account->position->childUsers->all(),
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'total_count' => User::find()->count(),
            'last_users' => User::find()->with('status')->orderBy(['created' => SORT_DESC])->limit(20)->all(),
            'top_users' => User::find()->joinWith('invite')->with('payment')->orderBy(['invite.count' => SORT_DESC])->limit(20)->all(),
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

                    \Yii::$app->mailer->compose('welcome', [
                        'login' => $registrationForm->login,
                        'password' => $registrationForm->password,
                        'sponsor' => $registrationForm->inviteId ? $registrationForm->parentInvite->user->login : null,
                        'code' => $registrationForm->activation->code
                    ])->setFrom(\Yii::$app->params['mailFrom'])
                        ->setTo($registrationForm->email)
                        ->setSubject('Welcome to DIAMOND REWARDS')
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
            'model' => $editProfile
        ]);
    }

    public function actionOrder() {
        $userStatus = $this->user->status;

        $statusOptions = [];
        if ($userStatus) {
            switch($userStatus->status) {
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
            'counts' => $this->counts
        ]);
    }

    public function actionPaymentHistory() {
        return $this->render('payment-history', [
            'account' => $this->account,
            'refLink' => $this->reflink,
            'counts' => $this->counts,
            'history' => $this->user->payment->getHistory()->with(['payment.user'])->all()
        ]);
    }

    public function actionInvitedUsers() {
        return $this->render('invited-users', [
            'account' => $this->account,
            'refLink' => $this->reflink,
            'counts' => $this->counts,
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

        return $this->redirect(['index']);
    }
}