<?php

namespace app\modules\order\service;

use app\common\base\ApiService;
use app\modules\order\model\OrderDetail;
use app\modules\order\model\Orders;
use app\modules\user\models\Account;

class OrderDetailService extends ApiService
{
    public function __construct()
    {
        parent::init();
        $this->model=new OrderDetail();
    }


}