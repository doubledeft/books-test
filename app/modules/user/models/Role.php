<?php

namespace app\modules\user\models;

use app\common\base\ApiModel;

class Role extends ApiModel
{
    public static function tableName(){
        return '{{%role}}';
    }
    public function rules(){
        return [
            [['name','description'], 'string'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}