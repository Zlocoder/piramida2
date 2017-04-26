<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Exception;

class EditProfile extends \yii\base\Model {
    public $photo;
    public $firstname;
    public $lastname;
    public $phone;
    public $skype;
    public $pmId;

    private $_user;

    public function rules() {
        return [
            [['pmId'], 'required', 'message' => 'Введите свой кошелек Perfect Money'],
            [['pmId', 'firstname', 'lastname'], 'string', 'max' => 25, 'message' => '{attribute} не может быть больше 25 символов'],
            [['pmId'], 'match', 'pattern' => '/^[Uu]\d+$/', 'message' => 'Номер кошелька должен быть в формате "Uxxxxxx"'],
            [['phone'], 'string', 'min' => 5, 'max' => 25,
                'tooShort' => 'Телефон не может быть менее 5 символов',
                'tooLong' => 'Телефон не может быть более 25 символов',
            ],
            [['skype'], 'string', 'min' => 3, 'max' => 25,
                'tooShort' => 'Скайп не может быть менее 3 символов',
                'tooLong' => 'Скайп не может быть более 25 символов',
            ],

            [['photo'], 'image',
                'mimeTypes' => 'image/png, image/jpeg, image/jpg',
                'maxSize' => '204800',
                'maxFiles' => 1,
                'message' => 'Ошибка загрузки файла',
                'notImage' => 'загруженный файл не является изображением',
                'tooBig' => 'Максимальный размер файла не должен превышать 200 Kb',
                'tooMany' => 'Нельзя загружать более 1 картинки',
                'wrongMimeType' => 'Непотдерживаемый формат файла'
            ],
        ];
    }

    public function attributeLabels() {
        return [
            'photo' => 'Фото',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'phone' => 'Скайп',
            'pmId' => 'Кошелек Perfect Money'
        ];
    }

    public function setUser($user) {
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->phone = $user->phone;
        $this->skype = $user->skype;
        $this->pmId = $user->payment->pmId;

        $this->_user = $user;
    }

    public function run() {
        if ($this->validate()) {
            $this->_user->firstname = $this->firstname;
            $this->_user->lastname = $this->lastname;
            $this->_user->phone = $this->phone;
            $this->_user->skype = $this->skype;

            if(!$this->_user->save()) {
                throw new Exception('Can not save user');
            };

            if ($this->photo) {
                $this->_user->createPhoto($this->photo);
            }

            $this->_user->payment->pmId = ucfirst($this->pmId);
            if (!$this->_user->payment->save()) {
                throw new Exception('Can not save user payment');
            }

            return true;
        }

        return false;
    }
}