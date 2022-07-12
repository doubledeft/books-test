<?php

namespace app\modules\user\models;

use app\common\base\ApiModel;

class Customer extends ApiModel
{
    public static function tableName(){
        return '{{%customer}}';
    }
    public function rules(){
        return [
            [['name','address','fax','email','bank','account','comment'], 'string'],
            [['tel'],'integer'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}
