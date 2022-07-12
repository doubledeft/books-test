<?php

namespace app\modules\contract\models;

use app\common\base\ApiModel;

class State extends ApiModel
{
    public static function tableName(){
        return '{{%contract_state}}';
    }
    public function rules(){
        return [
            [['time','type'], 'integer'],
            [['contract_id'], 'string'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}