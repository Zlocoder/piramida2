<?php

namespace app\models;

use app\base\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\Exception;
use yii\imagine\Image;

class User extends ActiveRecord implements IdentityInterface {
    // IdentityInterface
    public static function findIdentity($id) {
        return self::findOne($id);
    }

    public function getId() {
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return null;
    }

    public function getAuthKey() {
        return null;
    }

    public function validateAuthKey($authKey) {
        return false;
    }

    // ActiveRecord
    public static function tableName() {
        return 'user';
    }

    public function rules() {
        return [
            [['firstname', 'lastname', 'login', 'password', 'email', 'country'], 'required'],

            [['firstname', 'lastname', 'login', 'skype'], 'string', 'min' => 3, 'max' => 25],
            [['login', 'skype'], 'match', 'pattern' => '/^[^ ]{3,25}$/'],
            [['password'], 'string', 'min' => 60, 'max' => 60],
            [['email'], 'string', 'max' => 100],
            [['country'], 'string', 'max' => 100],
            [['phoneDigits', 'phone'], 'string', 'min' => 5, 'max' => 25],
            [['email'], 'email'],

            [['login', 'email'], 'unique'],

            [['created', 'updated'], 'safe']
        ];
    }

    // Invite Relations
    public function getInvite() {
        return $this->hasOne(Invite::className(), ['userId' => 'id']);
    }

    // Tree relations
    public function getPosition() {
        return $this->hasOne(Position::className(), ['userId' => 'id']);
    }

    // Invoice Relation
    public function getInvoices() {
        return $this->hasMany(Invoice::className(), ['userId' => 'id'])
            ->where([
                'or',
                ['invoiceStatus' => 'payed'],
                ['invoiceStatus' => 'complete']
            ])
            ->orderBy(['created' => SORT_DESC]);
    }

    // Status relation
    public function getStatus() {
        return $this->hasOne(UserStatus::className(), ['userId' => 'id']);
    }

    // Payment relation
    public function getPayment() {
        return $this->hasOne(UserPayment::className(), ['userId' => 'id']);
    }

    // Activation relation
    public function getActivation() {
        return $this->hasOne(UserActivation::className(), ['userId' => 'id']);
    }

    // Custom fields
    public function getFullname() {
        if ($this->firstname == $this->lastname) {
            return $this->firstname;
        }

        return $this->firstname . ' ' . $this->lastname;
    }

    public function getName() {
        if ($this->firstname) {
            return $this->getFullname();
        }

        return $this->login;
    }

    // Photo
    private static $_photoSizes = [
        [50, 50],
        [100, 100],
        [200, 200]
    ];

    public function createPhoto($uploadedPhoto) {
        if (!$this->id) {
            throw new Exception("Can not create photo. User not saved.");
        }

        $filename = \Yii::getAlias("@webroot/images/photo/{$this->id}");

        if (!$uploadedPhoto->saveAs("$filename.tmp")) {
            throw new Exception("Can not save uploaded file");
        };

        $imagine = Image::getImagine();
        $imagine->open("$filename.tmp")->save("$filename.png");
        unlink("$filename.tmp");

        foreach (self::$_photoSizes as $size) {
            Image::thumbnail("$filename.png", $size[0], $size[1])->save("$filename-{$size[0]}-{$size[1]}.png");
        }
    }

    public function getPhotoUrl($size = []) {
        if ($size && !in_array($size, self::$_photoSizes)) {
            throw new Exception('Wrong photo size');
        }

        $dirname = \Yii::getAlias('@webroot/images/photo');
        $sizePart = $size ? "-{$size[0]}-{$size[1]}" : '';
        if ($sizePart && !file_exists("$dirname/{$this->id}$sizePart.png") && file_exists("$dirname/{$this->id}.png")) {
            Image::thumbnail("$dirname/{$this->id}.png", $size[0], $size[1])->save("$dirname/{$this->id}$sizePart.png");
        }

        if (file_exists("$dirname/{$this->id}$sizePart.png")) {
            return \Yii::getAlias("@web/images/photo/{$this->id}$sizePart.png");
        }

        return \Yii::getAlias("@web/images/photo/default$sizePart.png");
    }

    public function getHasPhoto() {
        $dirname = \Yii::getAlias('@webroot/images/photo');
        return file_exists("$dirname/{$this->id}.png");
    }

    // Custom methods
    public function applyInvoice($invoice) {
        if ($invoice->userId != $this->id) {
            return false;
        }

        $status = $this->status;

        if (!$this->position) {
            $parentInvite = $this->invite->parent;
            $parentInvite->count += 1;
            if (!$parentInvite->save()) {
                throw new Exception('Can not update parent invite');
            };

            $this->populateRelation('position', $parentInvite->user->position->append($this->id));
        }

        $status->status = $invoice->userStatus;
        $status->active = new \yii\db\Expression('DATE_ADD(NOW(), INTERVAL 1 MONTH)');

        if (!$status->save()) {
            throw new Exception('Can not update UserStatus');
        };

        $this->payment->payed += $invoice->amount;
        if (!$this->payment->save()) {
            throw new Exception('Can not update payment');
        }
    }

    public function sendWelcomeMail() {

    }
}