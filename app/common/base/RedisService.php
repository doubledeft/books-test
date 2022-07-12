<?php
/**
 * redis service 服务类
 *
 */

namespace app\common\base;

use Yii;
use yii\base\Component;
use app\common\behaviors\ApiCommonBehavior;
use app\common\behaviors\RedisServiceBehavior;

class RedisService extends Component
{
    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge(parent::behaviors(), [
            'commonBehavior' => [
                'class' => ApiCommonBehavior::className(),
            ],
            'redisServiceBehavior' => [
                'class' => RedisServiceBehavior::className(),
            ],
        ]);
    }

    public function init()
    {
        parent::init();
    }
}
