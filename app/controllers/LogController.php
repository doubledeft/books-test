<?php

namespace app\controllers;
use app\common\base\ApiController;
class LogController extends ApiController
{
    //日志管理

    public function actionQuery(){
        $info=self::callModuleService('user','LogService','queryLog');
        return $info;
    }
    //用户进行的关于数据的增删改，添加日志

    //管理员对用户的增删和改权限，添加日志

    //管理员可以查询日志

    //管理可以备份和导出日志（导出可以做）

}