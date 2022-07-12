<?php

namespace app\modules\user\models;

use app\common\base\ApiModel;

class User extends ApiModel
{
    public static function tableName(){
        return '{{%user}}';
    }
    public function rules(){
        return [
            [['userid','nickname','password'],'string'],
        ];
    }
}