<?php

namespace app\models\forms;

use app\models\User;
use yii\data\ActiveDataProvider as DataProvider;

class UsersFilter extends \yii\base\Model {
    public $id;
    public $fullname;
    public $login;
    public $email;

    public function rules() {
        return [
            [['id'], 'integer'],
            [['fullname'], 'string', 'max' => 50],
            [['login'], 'string', 'max' => 25],
            [['email'], 'string', 'max' => 100]
        ];
    }

    public function getProvider() {
        $this->validate();

        $query = User::find()->with(['status', 'invite.parentUser']);

        if ($this->id && !$this->hasErrors('id')) {
            $query->andWhere(['id' => $this->id]);
        }

        if ($this->fullname && !$this->hasErrors('fullname')) {
            $query->andWhere(['or', ['like', 'firstname', $this->fullname], ['like', 'lastname', $this->fullname]]);
        }

        if ($this->login && !$this->hasErrors('login')) {
            $query->andWhere(['like', 'login', $this->login]);
        }

        if ($this->email && !$this->hasErrors('email')) {
            $query->andWhere(['like', 'email', $this->email]);
        }

        return new DataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => false,
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_DESC
                ]
            ]
        ]);
    }
}