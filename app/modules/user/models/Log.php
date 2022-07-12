<?php

namespace app\modules\user\models;

use app\common\base\ApiModel;

class Log extends ApiModel
{
    public static function tableName()
    {
        return '{{%log}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'create_timestamp', 'content'], 'string'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }
}
