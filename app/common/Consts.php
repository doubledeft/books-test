<?php
 
namespace app\common;
 
use app\modules\common\service\CollegeInfoService;
use Yii;
 
class Consts
{
    const ORDER_IN_CART=-1;
    const ORDER_NOT_PAY=0;
    const ORDER_PAY=1;
    const ORDER_NOT_DELIVER=2;
}