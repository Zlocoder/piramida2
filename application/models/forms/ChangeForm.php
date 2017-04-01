<?php

namespace app\models\forms;


class ChangeForm extends \yii\base\Model
{
    public $id;
    public $firstname;

    public function rules()
    {
        return [
            [['firstname'], 'required'],
            ['firstname', 'string'],
        ];
    }

    public function run(){
        $changeProfile = new Change([
            'firstname' => $this->firstname,
        ]);
        
        return $changeProfile->save();
    }
}