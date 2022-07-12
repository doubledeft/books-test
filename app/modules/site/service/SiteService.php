<?php

namespace app\modules\site\service;

use Yii;
use yii\helpers\ArrayHelper;
use app\helpers\DatetimeHelper;
use app\common\base\ApiService;
use app\modules\site\models\Site;

class SiteService extends ApiService
{
    function __construct()
    {
        parent::init();
        $this->model = new Site();
    }

    public function site()
    {
		// 逻辑处理
        return 'hello world!';

    }
}