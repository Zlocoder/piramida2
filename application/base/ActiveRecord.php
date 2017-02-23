<?php

namespace app\base;

use yii\db\ActiveRecord as AR;
use yii\behaviors\TimestampBehavior;

class ActiveRecord extends AR {
    // default behaviors config
    public $timestamp = [
        'createdAtAttribute' => 'created',
        'updatedAtAttribute' => 'updated',
    ];

    public function init() {
        // init behaviors
        if ($this->timestamp) {
            if (empty($this->timestamp['value'])) {
                $this->timestamp['value'] = new \yii\db\Expression('NOW()');
            }

            $this->attachBehavior('timestamp', new TimestampBehavior($this->timestamp));
        }
    }
}