<?php

namespace app\modules\user\models;

use app\common\base\ApiModel;

class Right extends ApiModel
{
    public static function tableName(){
        return '{{%right}}';
    }
    public function rules(){
        return [
            [['role_id'],'required'],
            ['role_id','string'],
            [['type','role'],'integer'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}