<?php

namespace app\modules\contract\models;

use app\common\base\ApiModel;

class  Process extends ApiModel
{
    public static function tableName(){
        return '{{%contract_process}}';
    }
    public function rules(){
        return [
            [['time','type'], 'integer'],
            [['contract_id','contract_name','user_id','content'], 'string'],
            [['state'], 'integer'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}