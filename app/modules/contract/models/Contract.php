<?php

namespace app\modules\contract\models;
use app\common\base\ApiModel;

class Contract extends ApiModel
{
    public static function tableName(){
        return '{{%contract}}';
    }
    public function rules(){
        return [
            [['beginTime', 'endTime',"createTime"], 'string'],
            [['name', 'user_id','customer','content','file'], 'string'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}