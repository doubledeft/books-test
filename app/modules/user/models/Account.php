<?php

namespace app\modules\user\models;

class Account extends \app\common\base\ApiModel
{
    public static function tableName(){
        return '{{%user_account}}';
    }
    public function rules(){
        return [
            [['userid','password'],'required'],
            [['type', 'create_timestamp', 'update_timestamp'], 'integer'],
            [['password', 'userid'], 'string'],
            ['is_user', 'in', 'range' => [0,1]],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}